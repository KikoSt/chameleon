<body>
<head>

</head>
<htlm>
    <?php
        foreach($this->templates as $template):
    ?>
            <img src="output/<?php echo $template;?>"/>
    <?php
        endforeach;
    ?>
</htlm>
</body>