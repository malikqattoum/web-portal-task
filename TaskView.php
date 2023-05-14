<?php

class TaskView {
    public function renderTable($tasks) {
        include 'views/table.php';
    }

    public function renderModal() {
        include 'views/modal.php';
    }

    public function renderTasks($tasks) {
        $output = "<ul>";
        foreach ($tasks as $task) {
            $output .= "<li>{$task['description']}</li>";
        }
        $output .= "</ul>";
        $output .= "<a href='/index.php'>back</a>";
        echo $output;
    }
    
    public function renderRefreshedTable($tasks)
    {
        include 'views/refreshed-table.php';
    }
    
}
