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
                
                let productPrice = parseFloat(product.Price).toFixed(2);
                
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
                
                productContainer.appendChild(newProduct);
            });
        })
        .catch(error => {
            console.error(error);
            alert("Unable to fetch data");
        });
}

function AddOrderFunction(id) {
    

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


    }

function deleteProduct(id, name) {
    if (confirm(`Delete the item: ${name}?`)) {
        fetch("../database/delete_product.php", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `deleteProductID=${id}`,
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error("Error: Unale to remove item");
                }
                window.location = "../database/delete_product.php";
            })
            .catch(error => {
                console.error(error);
                alert("Error: Unale to remove item");
            });
    }
}


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