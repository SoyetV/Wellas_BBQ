<script>
var menuOpen = false;

/* ------------------------------------------------ */

const menuScreenWidth = window.matchMedia("(max-width: 1200px)");

function mediaScreen(width) {
    if (!width.matches) {
        menuOpen = false;
        $(".Sidebar-Options").hide(0);
    }
}

menuScreenWidth.addEventListener("change", () => {
    mediaScreen(menuScreenWidth);
});

/* ------------------------------------------------ */

function updateFetch(dir) {

    fetch(dir)
        .then(response => {
            if (!response.ok) {
                throw new Error("Unable to fetch response");
            }
            return response.json();
        })
        .then(data => {
            const productContainer = document.getElementById("itemContainer");
            productContainer.innerHTML = ``;

            data.forEach(product => {
                
                const newProduct = document.createElement('div');
                newProduct.className = "item";

                let buttonPhrase = "ADD TO CART";
                <?php if(isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'Admin'): ?>
                    buttonPhrase = "EDIT MEAL";
                <?php endif; ?>

                let productPrice = parseFloat(product.Price).toFixed(2);
                
                <?php if(isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'Admin'): ?>
                    if (product.Img_Dir == null) {
                        newProduct.innerHTML = `<button class="deleteButton" onclick="deleteConfirmation(${product.ID}, '${product.Name}')"></button>
                                                <img src="../images/Icons/TemporaryImage.png" alt="}">
                                                <div class="name">${product.Name}</div>
                                                <div class="price">₱${productPrice}</div>
                                                <button class="itemButton" onclick="AddOrderFunction(${product.ID})">${buttonPhrase}</button>`;
                    } else {
                        newProduct.innerHTML = `<button class="deleteButton" onclick="deleteConfirmation(${product.ID}, '${product.Name}')"></button>
                                                <img src="../database/web_img/${product.Img_Dir}" alt="${product.Name}">
                                                <div class="name">${product.Name}</div>
                                                <div class="price">₱${productPrice}</div>
                                                <button class="itemButton" onclick="AddOrderFunction(${product.ID})">${buttonPhrase}</button>`;
                    }
                <?php else: ?>
                    if (product.Img_Dir == null) {
                        newProduct.innerHTML = `<img src="../images/Icons/TemporaryImage.png" alt="${product.Name}">
                                                <div class="name">${product.Name}</div>
                                                <div class="price">₱${productPrice}</div>
                                                <button class="itemButton" onclick="AddOrderFunction(${product.ID})">${buttonPhrase}</button>`;
                    } else {
                        newProduct.innerHTML = `<img src="../database/web_img/${product.Img_Dir}" alt="${product.Name}">
                                                <div class="name">${product.Name}</div>
                                                <div class="price">₱${productPrice}</div>
                                                <button class="itemButton" onclick="AddOrderFunction(${product.ID})">${buttonPhrase}</button>`;
                    }
                <?php endif; ?>

                productContainer.appendChild(newProduct);
            });
        })
        .catch(error => {
            console.error(error);
            alert("Unable to fetch data");
        });
}

function AddOrderFunction(id) {
    <?php if(!$conn): ?>
        window.location = "../main_pages/menu.php";

    <?php elseif(!isset($_SESSION['user_type'])): ?>


        const dir = "../main_pages/guest_log_in.php";
        fetch(dir, {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `productID=${id}`
            })
            .then(() => {
                window.location = dir;
            })
            .catch(error => {
                console.error(error);
                alert("Error: Unable to order product");
            });


    <?php elseif($_SESSION['user_type'] == 'Member' || $_SESSION['user_type'] == 'Guest' ): ?>


        const dir = "../user/order_product.php";
        fetch(dir, {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `productID=${id}`
            })
            .then(() => {
                window.location = dir;
            })
            .catch(error => {
                console.error(error);
                alert("Error: Unable to order product");
            });


    <?php elseif($_SESSION['user_type'] == 'Admin'): ?>


        const dir = "../admin/edit_product.php";
        fetch(dir, {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `productID=${id}`
            })
            .then(() => {
                window.location = dir;
            })
            .catch(error => {
                console.error(error);
                alert("Error: Unable to open product editor");
            });
    <?php endif; ?>
}

function deleteConfirmation(id, name) {
    $("#confirmBox").show(0);

    document.getElementById("confirmation-text").innerHTML = `Are you sure you want to remove the item: ${name}?`;
    document.getElementById("confirmation-confirm-button").onclick = function() {deleteProduct(id, name)};
}

function deleteProduct(id, name) {
    $("#confirmBox").hide(0);
    fetch("../database/delete_product.php", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `deleteProductID=${id}`,
        })
        .then(response => {
            if (!response.ok) {
                throw new Error("Error: Unable to remove item");
            }
            window.location = "../database/delete_product.php";
        })
        .catch(error => {
            console.error(error);
            alert("Error: Unable to remove item");
        });
}

<?php if(isset($_SESSION['user_type']) and $_SESSION['user_type'] == 'Admin'): ?>
    document.getElementById("addNewButton").addEventListener("click", () => {
        window.location = "../admin/create_product.php";
    });
<?php endif; ?>

/* ------------------------------------------------ */

const Pork = document.getElementById("Pork");
const Chicken = document.getElementById("Chicken");
const Others = document.getElementById("Others");

document.addEventListener("DOMContentLoaded", () => {
    $(".Sidebar-Options").hide(0);
    document.getElementById("Sidebar-Section").style.visibility = "visible";
    deactivateAllButtonPC();
    updateFetch("../database/data/pork.json");
    activateButtonPC(Pork);
});

function activateButtonPC(button) {
    button.style.backgroundColor = "#f9a833ff";
    button.style.color = "white";
}

function deactivateAllButtonPC() {
    deactivateButtonPC(Pork);
    deactivateButtonPC(Chicken);
    deactivateButtonPC(Others);
}

function deactivateButtonPC(button) {
    button.style.backgroundColor = "white";
    button.style.color = "rgb(67, 44, 26)";
}

function changeMenuPC(button) {
    deactivateAllButtonPC();

    switch(button.innerHTML) {
    case "PORK":
        updateFetch("../database/data/pork.json");
        activateButtonPC(Pork);
        break;
    case "CHICKEN":
        updateFetch("../database/data/chicken.json");
        activateButtonPC(Chicken);
        break;
    case "OTHERS":
        updateFetch("../database/data/others.json");
        activateButtonPC(Others);
        break;
}

    const mainButton = document.getElementById("MenuButton");
    mainButton.innerHTML = button.innerHTML;
}

// REQUIRES JQUERY

document.getElementById("MenuButton").addEventListener('click', () => {
    if (!menuOpen) {
        menuOpen = true;
        $(".Sidebar-Options").slideDown("fast", "swing");
    } else {
        menuOpen = false;
        $(".Sidebar-Options").slideUp("fast", "swing");
    }
});

function changeMenu(button) {

    deactivateAllButtonPC();

    switch(button.innerHTML) {
    case "PORK":
        updateFetch("../database/data/pork.json");
        activateButtonPC(Pork);
        break;
    case "CHICKEN":
        updateFetch("../database/data/chicken.json");
        activateButtonPC(Chicken);
        break;
    case "OTHERS":
        updateFetch("../database/data/others.json");
        activateButtonPC(Others);
        break;
}

    const mainButton = document.getElementById("MenuButton");
    mainButton.innerHTML = button.innerHTML;

    $(".Sidebar-Options").slideUp("fast", "swing");
    menuOpen = !menuOpen;
}
</script>