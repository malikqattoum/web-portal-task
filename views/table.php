<!DOCTYPE html>
<html>
  <head>
    <title>Task</title>
    <style>
      .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
      }
      
      .modal-content {
        background-color: #fefefe;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
      }
      
      .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
      }
      
      .close:hover,
      .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
      }
      
      button {
        background-color: #4CAF50;
        border: none;
        color: white;
        padding: 10px 20px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin-bottom: 10px;
        cursor: pointer;
      }
    </style>
  </head>
  <body>
  
  <form id="search-form" action="/index.php?action=search" method="POST">
    <label for="search">Search:</label>
    <input type="text" id="search" name="search">
    <button type="submit">Search</button>
  </form>

<hr>
<div id="task-list">
    <table>
        <thead>
            <tr>
            <th>ID</th><th>Title</th><th>Description</th><th>Color Code</th>
            </tr>
        </thead>
        
        <tbody>
            <?php foreach ($tasks as $task): ?>
            <tr>
                <td><?= $task->task ?></td>
                <td><?= $task->title ?></td>
                <td><?= $task->description ?></td>
                <td><?= $task->colorCode ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function() {
  function refreshTasks() {
    console.log('Refreshing task list...');
    $.get('/index.php?action=refresh', function(data) {
      $('#task-list').html(data);
    }).fail(function(xhr, status, error) {
      console.log("Error refreshing task list: " + error);
    });
  }

  setInterval(refreshTasks, 60000);

  const openModalBtn = document.getElementById('open-modal-btn');
      const modal = document.getElementById('myModal');
      const closeModalBtn = document.getElementsByClassName('close')[0];
      const fileInput = document.querySelector('input[type=file]');
      const selectedImageContainer = document.getElementById('selected-image-container');

      openModalBtn.addEventListener('click', function() {
        modal.style.display = 'block';
      });

      closeModalBtn.addEventListener('click', function() {
        modal.style.display = 'none';
      });

      fileInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        const reader = new FileReader();
        reader.onload = function(event) {
          const img = new Image();
          img.src = event.target.result;
          selectedImageContainer.appendChild(img);
        };
        reader.readAsDataURL(file);
      });
});
</script>
</body>
</html>