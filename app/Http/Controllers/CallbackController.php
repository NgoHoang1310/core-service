<?php

namespace App\Http\Controllers;

use App\Models\Video_Quality;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CallbackController extends ApiController
{
    public function updateVideoQuality(Request $request)
    {
        $request->validate([
            'video_id' => 'required|integer',
            'urls' => 'required|array',
        ]);

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
