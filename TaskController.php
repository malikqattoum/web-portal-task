<?php

class TaskController {
    private $model;
    private $view;

    public function __construct($view) {
        $this->view = $view;
    }

    public function index() {
        $accessToken = $this->authorize();
        if (!$accessToken) {
            return;
        }
        
        try {
            $this->model = new TaskModel($accessToken);
    
            $tasks = $this->model->getAll($accessToken);
    
            $this->view->renderTable($tasks);
    
            $this->view->renderModal();
        } catch (Exception $e) {
            echo "Error retrieving tasks: " . $e->getMessage();
        }
    }
    
    public function authorize() {
        $ch = curl_init();
        $url = "https://api.baubuddy.de/index.php/login";
        $data = [
            'username' => '365',
            'password' => '1'
        ];
        $headers = [
            'Authorization: Basic QVBJX0V4cGxvcmVyOjEyMzQ1NmlzQUxhbWVQYXNz',
            'Content-Type: application/json'
        ];
    
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => $headers,
        ));
        $accessToken = null;
        try {
            $jsonData = curl_exec($ch);
            if (curl_errno($ch)) {
                throw new Exception("cURL Error #:" . curl_error($ch));
            }
            $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($responseCode < 200 || $responseCode >= 400) {
                throw new Exception("HTTP response code error: " . $responseCode);
            }
            $data = json_decode($jsonData, true);
            if (!isset($data['oauth']['access_token'])) {
                throw new Exception("Access token not found in API response");
            }
            $accessToken = $data['oauth']['access_token'];
        } catch (Exception $e) {
            echo "Error during authorization: " . $e->getMessage();
            return null;
        } finally {
            curl_close($ch);
        }
        
        return $accessToken;
    }
    
    public function search() {
        $accessToken = $this->authorize();
        if (!$accessToken) {
            return;
        }
        if (isset($_POST['search'])) {
            $searchTerm = $_POST['search'];
            $url = 'https://api.baubuddy.de/dev/index.php/v1/tasks/select?description=' . urlencode($searchTerm);
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Authorization: Bearer ' . $accessToken,
                "Content-Type: application/json"
            ));
            
            $tasks = curl_exec($ch);
            curl_close($ch);
            
            $tasks = json_decode($tasks, true);
            $this->view->renderTasks($tasks);
        } else {
            http_response_code(400);
            echo "Bad request";
        }
    }
    

    public function refresh() {
        $accessToken = $this->authorize();
        if (!$accessToken) {
            return;
        }
        
        try {
            $this->model = new TaskModel($accessToken);
    
            $tasks = $this->model->getAll($accessToken);
    
            $this->view->renderRefreshedTable($tasks);
        } catch (Exception $e) {
            echo "Error retrieving tasks: " . $e->getMessage();
        }
    }

    public function updateTable()
    {
        $accessToken = $this->authorize();
        if (!$accessToken) {
            return;
        }
        
        try {
            $this->model = new TaskModel($accessToken);

            $tasks = $this->model->getAll($accessToken);
            
            header('Content-Type: application/json');
            echo json_encode($tasks);
        } catch (Exception $e) {
            http_response_code(500);
            echo "Error retrieving tasks: " . $e->getMessage();
        }
    }

}
