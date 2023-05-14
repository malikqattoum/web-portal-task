<?php

class TaskModel {
    private $tasks = array();
    private $accessToken;

    public function __construct($accessToken) {
        $this->accessToken = $accessToken;
    }

    public function getAll() {

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.baubuddy.de/dev/index.php/v1/tasks/select",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer " . $this->accessToken,
                "Content-Type: application/json"
            ],
        ]);
        $jsonData = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            throw new Exception("Error fetching tasks: " . $err);
        } else {

            $data = json_decode($jsonData, true);
            foreach ($data as $taskData) {
                $task = new stdClass();
                $task->task = $taskData['task'];
                $task->title = $taskData['title'];
                $task->description = $taskData['description'];
                $task->sort = $taskData['sort'];
                $task->wageType = $taskData['wageType'];
                $task->BusinessUnitKey = $taskData['BusinessUnitKey'];
                $task->businessUnit = $taskData['businessUnit'];
                $task->parentTaskID = $taskData['parentTaskID'];
                $task->preplanningBoardQuickSelect = $taskData['preplanningBoardQuickSelect'];
                $task->colorCode = $taskData['colorCode'];
                $task->workingTime = $taskData['workingTime'];
                $task->isAvailableInTimeTrackingKioskMode = $taskData['isAvailableInTimeTrackingKioskMode'];
                $this->tasks[] = $task;
            }
        }

        return $this->tasks;
    }
    
}
