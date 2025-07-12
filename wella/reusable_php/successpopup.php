<style>

#successBox {
    width: 100%;
    top: 0;
    margin-top: 130px;
    position: fixed;
    z-index: 999;
    display: flex;
    justify-content: center;
    align-items: center;
}

#success-box {
    position: fixed;
    left: auto;
    right: auto;
    background-color: white;
    box-shadow: 0 4px 8px 0 rgba(223, 89, 27, 0.2), 0 6px 20px 0 rgba(223, 89, 27, 0.2);
    padding-inline: 30px;
    width: 100%;
    min-width: 750px;
    max-width: 750px;
    position: relative;
    border-radius: 10px;
    word-wrap: break-word;
    background-color: red;
    color: white;
}

#successful-text {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-weight: 600;
    font-size: 19px;
    text-align: center;
    margin-top: -25px;
}

#successful-close {
    font-family:Arial, Helvetica, sans-serif;
    font-family:Verdana, Geneva, Tahoma, sans-serif;
    font-weight: 600;
    font-size: 40px;
    position: absolute;
    top: 0;
    right: 0;
    margin-top: -5px;
    margin-right: 20px;
    cursor: pointer;
    transition: scale 0.1s ease-in-out;
}

@media all and (max-width: 1200px) {
    #successBox {
        scale: 0.8;
    }

    #success-box {
        min-width: 0;
        max-width: 100%;
    }
}

</style>

<div id="successBox">
    <div id="success-box">
        <span>
            <p id="successful-text">_</p>
            <p id="successful-close">X</p>
        </span>
    </div>
</div>

<script>
    $("#successBox").fadeIn("fast", "swing");

    setInterval(closeErrorPopUp, 5000);

    document.getElementById("successful-close").addEventListener('click', () => {
        closeErrorPopUp();
    })

    function closeErrorPopUp() {
        $("#successBox").fadeOut("fast", "swing");
    }
</script>