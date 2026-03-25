<script>
function makeRequest() {
  $.ajax({
    url: "../includes/process_request.php?id=<?php echo $_SESSION['unique_id']; ?>",
    type: "POST",
    success: function(response) {
      if(response.length != 0) {
        window.location.href = response;
      } else {
        console.log("Empty response received");
      } 
    },
    error: function(xhr, status, error) {
      console.error("Request failed with status: " + xhr.status);
    }
  });
}
</script>
