    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container">
        <h1 class="display-3">User List</h1>
        <p>This is a list of current users registered in the system.</p>
        <ul>
          <?php foreach ($params['data'] as $username): ?>
            <li><?php echo $username; ?></li>
          <?php endforeach; ?>
        </ul>
        <p><a class="btn btn-primary btn-lg" href="#" role="button">View more &raquo;</a></p>
      </div>
    </div>
