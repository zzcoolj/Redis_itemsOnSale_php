<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>POC - REDIS (PHP)</title>

    <meta name="description" content="Source code generated using layoutit.com">
    <meta name="author" content="LayoutIt!">

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <nav class="navbar navbar-default" role="navigation">
                        <div class="navbar-header">

                            <button type="button" class="navbar-toggle" data-toggle="collapse"
                                    data-target="#bs-example-navbar-collapse-1">
                                <span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span
                                    class="icon-bar"></span><span class="icon-bar"></span>
                            </button>
                            <a class="navbar-brand" href="#">POC - Redis (PHP)</a>
                        </div>

                        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                            <ul class="nav navbar-nav">
                                <li class="active">
                                    <a href="#">Products</a>
                                </li>
                                <li>
                                    <a href="#">Client</a>
                                </li>
                            </ul>
                        </div>

                    </nav>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <form class="form-inline" role="form">
                                <div class="form-group">
                                    <label for="name">
                                        Name
                                    </label>
                                    <input class="form-control" id="name" type="text">
                                </div>
                                <div class="form-group">
                                    <label for="price">
                                        Price
                                    </label>
                                    <input class="form-control" id="price" type="text">
                                </div>
                                <div class="form-group">
                                    <label for="category">
                                        Category
                                    </label>
                                    <input class="form-control" id="category" type="text">
                                </div>
                                <div class="form-group">
                                    <label for="description">
                                        Description
                                    </label>
                                    <textarea class="form-control" id="description" rows="1">Description</textarea>
                                </div>
                                <button type="button" class="btn btn-default" id="submit">
                                    Submit
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div id="contentContainer">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>
                                            Name
                                        </th>
                                        <th>
                                            Price
                                        </th>
                                        <th>
                                            category
                                        </th>
                                        <th>
                                            Description
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody id="table">
                                    <tr>
                                        <td>
                                            Product1
                                        </td>
                                        <td>
                                            300
                                        </td>
                                        <td>
                                            category1
                                        </td>
                                        <td>
                                            keyword1 keyword2 keyword3
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/scripts.js"></script>
</body>
</html>