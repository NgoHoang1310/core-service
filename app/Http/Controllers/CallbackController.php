<?php

namespace App\Http\Controllers;

use App\Models\Episode;
use App\Models\Movie;
use App\Models\Notification;
use App\Models\Series;
use App\Models\Video_Quality;
use App\Services\Queue\Producers\SocketProducer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CallbackController extends ApiController
{
    protected SocketProducer $socketProducer;

    public function __construct(SocketProducer $socketProducer)
    {
        $this->socketProducer = $socketProducer;
    }

    public function updateVideoQuality(Request $request)
    {
        $request->validate([
            'video_id' => 'required|integer',
            'urls' => 'required|array',
        ]);

        $table = $request->get('video_type') == Movie::CONTENT_TARGET_TYPE_MOVIE
            ? Movie::class
            : Episode::class;

        $movie = $table::query()
            ->where('id', $request->get('video_id'))
            ->where('status', Movie::STATUS_ACTIVE)
            ->first();


        Log::error($movie);

        $videoQualities = Video_Quality::where([
            ['video_id', '=', $request->get('video_id')],
            ['video_type', '=', $request->get('video_type')],
        ])->get();

        if ($videoQualities->isEmpty()) {
            return $this->errorResponse('Không tìm thấy video chất lượng phù hợp', 404);
        }

        $data = [];

        foreach ($videoQualities as $videoQuality) {
            $quality = $videoQuality->quality;
            $videoQuality->update([
                'status' => $request->get('status'),
                'video_url' => $request->urls[$quality] ?? null,
                'metadata' => json_encode($request->get('metadata')) === 'null' ? null : json_encode($request->get('metadata')),
            ]);
            $data[] = $videoQuality->fresh();
        }

        $notification = new Notification();
        $notification->title = $request->get('video_type') === Movie::CONTENT_TARGET_TYPE_MOVIE ? 'Phim mới cập nhật' : 'Tập mới cập nhật';
        $notification->content = $request->get('video_type') === Movie::CONTENT_TARGET_TYPE_MOVIE ? $movie->title : $movie->title . ' của ' . $movie->series->title . ' đã vừa mới được cập nhật';
        $notification->type = Notification::BROADCAST;
        $notification->target_id = $request->get('video_id');
        $notification->payload = json_encode($movie);
        $notification->save();

        $this->socketProducer->processSocket(Notification::resolveRoom(Notification::BROADCAST), $notification->toArray());

        $tempPath = $request->get('tempPath');
        // Gọi đến API tạm thời để xóa file đã upload
        if (!empty($tempPath)) {
            try {
                // Tạo payload với id là đường dẫn video
                $payload = [
                    'id' => $tempPath,
                ];

                // Gửi yêu cầu POST tới API revert
                Http::delete('http://127.0.0.1:8085/upload/temp/revert', $payload);
            } catch (\Exception $e) {
                // Log lỗi hoặc tiếp tục nếu cần
            }
        }

        return $this->successResponse($data, 'Video quality updated successfully');
    }

}
