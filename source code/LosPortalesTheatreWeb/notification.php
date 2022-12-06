
<!-- Modal -->
<div class="modal fade" id="noti" tabindex="-1" aria-labelledby="notiLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="notiLabel">Alert</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php
                if(isset($_SESSION["message"])){
                    if($_SESSION["message"]!=""){
                        echo "<script>pAlert('#noti')</script>";
                        echo "<p class='nMessage'>{$_SESSION["message"]}</p>";
                        $_SESSION["message"] = "";
                    }
             
             }
        ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

