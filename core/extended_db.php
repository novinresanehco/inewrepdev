<?php
class ExtendedDBClass extends dbclass {
    private function processStar($data) {
        // اول یک لاگ از مقدار اولیه می‌گیریم
        if (isset($data['star'])) {
            $this->logDebug('[Star] Original value', ['value' => $data['star'], 'type' => gettype($data['star'])]);
        }

        // بررسی وجود star در دیتا
        if (!isset($data['star'])) {
            $data['star'] = 0;
            $this->logDebug('[Star] No value sent, setting default', ['value' => 0]);
            return $data;
        }

        // پردازش مقدار star
        $originalValue = $data['star'];
        
        // تبدیل انواع مقادیر به 0 یا 1
        if (is_string($originalValue)) {
            if ($originalValue === 'on' || $originalValue === '1') {
                $data['star'] = 1;
            } else {
                $data['star'] = 0;
            }
        } else {
            $data['star'] = !empty($originalValue) ? 1 : 0;
        }

        // لاگ کردن نتیجه پردازش
        $this->logDebug('[Star] Processed value', [
            'original' => $originalValue,
            'originalType' => gettype($originalValue),
            'processed' => $data['star']
        ]);

        return $data;
    }

    public function iquery($table, $data) {
        try {
            if ($table === 'data') {
                $this->logDebug('[iQuery] Before processing', ['data' => $data]);
                $data = $this->processStar($data);
                $this->logDebug('[iQuery] After processing', ['data' => $data]);
            }
            return parent::iquery($table, $data);
        } catch (\Exception $e) {
            $this->logDebug('[iQuery] Error', [
                'message' => $e->getMessage(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    public function uquery($table, $data, $where) {
        try {
            if ($table === 'data') {
                $this->logDebug('[uQuery] Before processing', ['data' => $data]);
                $data = $this->processStar($data);
                $this->logDebug('[uQuery] After processing', ['data' => $data]);
            }
            return parent::uquery($table, $data, $where);
        } catch (\Exception $e) {
            $this->logDebug('[uQuery] Error', [
                'message' => $e->getMessage(),
                'data' => $data,
                'where' => $where
            ]);
            throw $e;
        }
    }

    protected function logDebug($message, $context = []) {
        $logFile = dirname(__DIR__) . '/logs/db-debug.log';
        $logEntry = sprintf(
            "[%s] %s | Data: %s\n",
            date('Y-m-d H:i:s'),
            $message,
            json_encode($context, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );
        file_put_contents($logFile, $logEntry, FILE_APPEND);

        if (isset($context['error'])) {
            error_log($logEntry);
        }
    }
}