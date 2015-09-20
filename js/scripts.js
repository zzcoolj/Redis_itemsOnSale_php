// Empty JS for your own code to be here


$(document).ready(function () {

    // Initialise the talbe with content already loaded in REDIS
    printAllProduct();

    // Add a new product with ajax call
    $("#submit").click(function () {
        var productName = $("#name").val();
        var productPrice = $("#price").val();
        var productCategory = $("#category").val();
        var productDescription = $("textarea#description").val();
        var dataContainer = "productName=" + productName + "&productPrice=" + productPrice + "&productCategory=" + productCategory + "&productDescription=" + productDescription;
        $.ajax({
            type: "POST",
            url: "addProduct.php",
            data: dataContainer,
            success: function (data) {
                // Case data was succefully loaded in REDIS => Append the new added product to the HTML Table
                appendProductToTable(data);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                // Case the loading process failed => Print the code error (please see apache log for debug insights)
                alert(xhr.status + " : " + thrownError);
            }
        });
    });
});

// Search all product in the REDIS database and print them on the HTML Page as Table
function printAllProduct() {
    $(document).ready(function () {
        $.ajax({
            type: "POST",
            url: "getAllProduct.php",
            success: function (data) {
                // Create the HTML Table in the index page
                $("#contentContainer").html("<table class='table'><thead> <tr> <th>Name </th> <th>Price</th><th>category</th><th>Description</th></tr></thead><tbody id='table'>");
                parsedData = jQuery.parseJSON(data);
                // Loop throw the data set returned by the PHP Controller and append each one to the HTML table
                for (var i = 0; i < parsedData.length; i++) {
                    $("#table").append("<tr> <td>" + parsedData[i].name + "</td> <td>" + parsedData[i].price + "</td> <td>" + parsedData[i].category + "</td><td>" + parsedData[i].description + "</td> </tr>");
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                // PHP Controller Failing case
                alert(xhr.status + " : " + thrownError);
            }
        });
    });
}

// Append the product with the given productId to the Table
function appendProductToTable(productKey) {
    $(document).ready(function () {
        console.log(productKey);
        $.ajax({
            type: "POST",
            url: "getProductByKey.php",
            data: "productKey=" + productKey,
            success: function (data) {
                // Parsed returned string to JSON Object
                parsedData = jQuery.parseJSON(data);
                $("#table").append("<tr> <td>" + parsedData.name + "</td> <td>" + parsedData.price + "</td> <td>" + parsedData.category + "</td><td>" + parsedData.description + "</td> </tr>");
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status + " : " + thrownError);
            }
        });
    });
}