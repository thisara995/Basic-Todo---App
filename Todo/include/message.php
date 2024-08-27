<?php
    if(isset($_SESSION['message'])) :
?>

    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong><?= $_SESSION['message']; ?></strong> 
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

<?php 
    unset($_SESSION['message']);
    endif;
?>

<?php
    if(isset($_SESSION['message_error'])) :
?>

    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong><?= $_SESSION['message_error']; ?></strong> 
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

<?php 
    unset($_SESSION['message_error']);
    endif;
?>
