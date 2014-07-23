<body>
<head>

</head>
<html>
    <form method="post" action="index?page=editor">
    <?php
        if(!is_array($this->templates)):
    ?>
            <h1>An error occurred. Please try reloading the page ...</h1>
            <img src="asset/500.jpg"/>
    <?php
        else:

        foreach($this->templates as $template):
    ?>
            <img src="<?php echo $template;?>"/>
    <?php
        endforeach;
        endif;
    ?>
    </form>
</html>
</body>