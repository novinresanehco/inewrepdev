<?php
class ExtendedDBClass extends dbclass {
    public function iquery($table, $data) {
        // پردازش خاص برای جدول data و فیلد star
        if ($table === 'data') {
            if (isset($data['star'])) {
                // تبدیل مقادیر مختلف به 0 یا 1
                if ($data['star'] === 'on' || $data['star'] === '1' || $data['star'] === 1) {
                    $data['star'] = 1;
                } else {
                    $data['star'] = 0;
                }
            } else {
                // اگر مقداری ارسال نشده، پیش‌فرض 0
                $data['star'] = 0;
            }
        }
        return parent::iquery($table, $data);
    }

    public function uquery($table, $data, $where) {
        // همان منطق پردازش برای به‌روزرسانی
        if ($table === 'data') {
            if (isset($data['star'])) {
                if ($data['star'] === 'on' || $data['star'] === '1' || $data['star'] === 1) {
                    $data['star'] = 1;
                } else {
                    $data['star'] = 0;
                }
            } else {
                $data['star'] = 0;
            }
        }
        return parent::uquery($table, $data, $where);
    }

    // افزودن متد کمکی برای لاگ کردن خطاها
    protected function logError($message, $data = []) {
        $logFile = __DIR__ . '/logs/db-errors.log';
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = sprintf(
            "[%s] %s | Data: %s\n",
            $timestamp,
            $message,
            json_encode($data)
        );
        file_put_contents($logFile, $logMessage, FILE_APPEND);
    }
}
