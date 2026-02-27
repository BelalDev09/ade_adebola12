<?php

namespace App\Helper;

use Exception;
use App\Traits\apiresponse;
use Illuminate\Support\Str;
use Kreait\Firebase\Factory;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;

class Helper
{
    use apiresponse;

    public static function fileUpload($file, $folder, $name)
{
    try {
        $extension   = strtolower($file->getClientOriginalExtension());
        $safeName    = Str::slug($name);
        $filename    = $safeName . '-' . time() . '.' . $extension;
        $destination = public_path('uploads/' . $folder);

        if (!file_exists($destination)) {
            mkdir($destination, 0755, true);
        }

        $file->move($destination, $filename);

        return 'uploads/' . $folder . '/' . $filename;
    } catch (\Exception $e) {
        return null;
    }
}

    //tableCheckbox
    public static function tableCheckbox($row_id)
    {
        return '<div class="form-checkbox">
                    <input type="checkbox" class="form-check-input select_data" id="checkbox-' . $row_id . '" value="' . $row_id . '" onClick="select_single_item(' . $row_id . ')">
                    <label class="form-check-label" for="checkbox-' . $row_id . '"></label>
                </div>';
    }

    //video upload
    public static function videoUpload($file, $folder, $name)
    {
        try {
            $videoName = Str::slug($name) . '.' . $file->extension();
            $file->move(public_path('uploads/' . $folder), $videoName);
            $path = 'uploads/' . $folder . '/' . $videoName;
            return $path;
        } catch (Exception $e) {
            Log::error("Video upload error: " . $e->getMessage());
            return null;
        }
    }

    // audio upload
    public static function audioUpload($file, $folder, $name)
    {
        try {
            $audioName = Str::slug($name) . '.' . $file->extension();
            $file->move(public_path('uploads/' . $folder), $audioName);
            $path = 'Uploads/' . $folder . '/' . $audioName;
            return $path;
        } catch (Exception $e) {
            Log::error("Audio upload error: " . $e->getMessage());
            return null;
        }
    }

    public static function deleteFile($path)
    {
        try {
            if ($path && file_exists(public_path($path))) {
                unlink(public_path($path));
                return true;
            }
            return false;
        } catch (Exception $e) {
            Log::error('File deletion error: ' . $e->getMessage());
            return false;
        }
    }

    public static function getFileType(string $extension): string
    {
        $imageTypes = ['jpg', 'jpeg', 'png', 'webp', 'svg'];
        $videoTypes = ['mp4', 'mov', 'avi'];
        $documentTypes = ['pdf', 'doc', 'docx'];

        $extension = strtolower($extension);
        if (in_array($extension, $imageTypes)) {
            return 'image';
        }
        if (in_array($extension, $videoTypes)) {
            return 'video';
        }
        if (in_array($extension, $documentTypes)) {
            return 'document';
        }
        return 'other';
    }

    private static function formatGroup($days, $hours)
    {
        if (empty($hours['start_time']) || empty($hours['end_time'])) {
            return count($days) > 1 ? "{$days[0]} - {$days[count($days) - 1]}: Closed" : "{$days[0]}: Closed";
        }
        $startTime = date('h:i A', strtotime($hours['start_time']));
        $endTime = date('h:i A', strtotime($hours['end_time']));
        $timeRange = "{$startTime} - {$endTime}";
        return count($days) > 1 ? "{$days[0]} - {$days[count($days) - 1]}: {$timeRange}" : "{$days[0]}: {$timeRange}";
    }


    public static function sendNotifyMobile($token, $notifyData): void
    {
        try {
            $factory = (new Factory)->withServiceAccount(storage_path(env('FIREBASE_CREDENTIALS')));
            $messaging = $factory->createMessaging();
            $notification = Notification::create($notifyData['title'], Str::limit($notifyData['body'], 100), $notifyData['icon']);
            $message = CloudMessage::withTarget('token', $token)->withNotification($notification);
            $messaging->send($message);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
        return;
    }

}
