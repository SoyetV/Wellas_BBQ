<link href="../css/reusable_php_css/confirmationpopup.css" rel="stylesheet">

<style>

#confirmBox {
    display: none;
}

</style>

<div id="confirmBox">
    <div class="darkednedBackground">
        <div id="confirmation-box">
            <div id="confirmation-close"></div>
            <h1 id="confirmation-title">CONFIRMATION</h1><br>
            <p id="confirmation-text">___</p>
            <span>
                <button class="confirmation-buttons" id="confirmation-cancel-button">CANCEL</button>
                <button class="confirmation-buttons" id="confirmation-confirm-button">CONFIRM</button>
            </span>
        </div>
    </div>
</div>

<script>
    document.getElementById("confirmation-close").addEventListener('click', () => {
        $("#confirmBox").hide(0);
    })

    document.getElementById("confirmation-cancel-button").addEventListener('click', () => {
        $("#confirmBox").hide(0);
    })
</script>


