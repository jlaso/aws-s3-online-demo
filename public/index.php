<?php

    include __DIR__ . "/../vendor/autoload.php";

    use JLaso\S3Wrapper\S3Wrapper;

    $awsKey = $awsSecret = $bucket = $current = $localFile = "";
    $files = array();

    if (isset($_POST['aws-key']) && isset($_POST['aws-secret']) && isset($_POST['bucket'])) {

        $awsKey = $_POST['aws-key'];
        $awsSecret = $_POST['aws-secret'];
        $bucket = $_POST['bucket'];

        $s3 = new S3Wrapper($awsKey, $awsSecret, $bucket);

        $files = $s3->getFilesList("");

        if (isset($_POST['file']) && !empty($_POST['file'])) {
            $current = $_POST['file'];
            $localFile = __DIR__ . "/../cache/" . str_replace("/", "_", $current);
            $s3->getFileIfNewest($localFile, $current);
        }

    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="favicon.ico?v=1"/>
    <title>AWS S3 online demo by JLaso</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" integrity="sha512-dTfge/zgoMYpP7QbHy4gWMEGsbsdZeCXz7irItjcC3sPUFtf0kuFbDz/ixG7ArTxmDjLXDmezHubeNikyKGVyQ==" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css" integrity="sha384-aUGj/X2zp5rLCbBxumKTCw2Z50WgIr1vs/PFN4praOTvYXWlVyh2UtNUU0KAUhAX" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js" integrity="sha512-K1qjQ+NcF2TYO/eI3M6v8EiNYZfA95pQumfvcVrTHtwQVDG+aHRqLi/ETn2uB+1JqwYqVG3LIvdm9lj6imS/pQ==" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        body {
            padding-top: 40px;
            padding-bottom: 40px;
        }
    </style>
</head>

<body>

<div id="container" class="container">

    <div id="alert-pos"></div>

    <div class="row">
        <h1 class="text-center">AWS S3 demo</h1>
        <p class="text-center">
            <img src="img/cloud-storage3.png">
        </p>
    </div>

    <form id="main-form" method="post" action="#">
        <label for="aws-key" class="sr-only">AWS Key</label>
        <input type="text" name="aws-key" class="form-control" placeholder="AWS Key" value="<?php echo $awsKey; ?>" required autofocus>
        <label for="aws-secret" class="sr-only">AWS Secret</label>
        <input type="password" name="aws-secret" class="form-control" placeholder="AWS Secret" value="<?php echo $awsSecret; ?>" required>
        <label for="bucket" class="sr-only">AWS Secret</label>
        <input type="text" name="bucket" class="form-control" placeholder="bucket" value="<?php echo $bucket; ?>" required>

        <?php if (count($files) > 0): ?>
            <h2>Files present on the bucket</h2>
            <table class="table table-bordered">
                <thead>
                <tr><td>Select one</td><td>File</td></tr>
                </thead>
        <?php endif; ?>

        <?php foreach ($files as $file): ?>

            <?php if (!preg_match("/\/$/", $file['filename'])): ?>

                <tr>
                    <td><input type="radio" <?php echo ($file['filename'] == $current) ? "checked" : "" ?> name="file" value="<?php echo $file['filename']; ?>"></td>
                    <td><?php echo $file['filename']; ?></td>
                </tr>

                <?php if ($file['filename'] == $current): ?>

                    <tr>
                        <td colspan="2">
                            <h4>Content of the file <?php echo $file['filename']; ?></h4>
                            <?php echo file_get_contents($localFile); ?>
                        </td>
                    </tr>

                <?php endif; ?>

            <?php endif; ?>

        <?php endforeach; ?>

        <?php if (count($files) > 0): ?>
            </table>
        <?php endif; ?>

        <input class="btn btn-lg btn-primary btn-block ajax-no" id="button" type="submit" value="Access" />

        <p>The data provide is not stored. It is only used to connect to your AWS bucket in this request.</p>
    </form>


</div> <!-- /container -->

<div class="container-fluid">
    <div class="row">
        <p class="text-center">
            <img src="img/ajax-loader.gif" class="ajax-yes" id="loader">
        </p>
    </div>

    <div class="row">
        <p class="text-center">
            You can get the class <a target="_blank" href="https://github.com/jlaso/aws-s3-wrapper">here</a>
        </p>
    </div>
</div>

<div class="footer">
    <div class="container">
        <p class="text-center">Favicon and icon made by <a href="http://www.freepik.com" title="Freepik">Freepik</a> from <a href="http://www.flaticon.com" title="Flaticon">www.flaticon.com</a> is licensed under <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0">CC BY 3.0</a></p>
        <p class="text-center">Please, take in account that the use of this site could consume the read credits you have in your AWS account.</p>
    </div>
</div>

<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="js/ie10-viewport-bug-workaround.js"></script>

</body>

<script>

</script>
</html>