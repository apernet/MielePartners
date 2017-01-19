    <div  class="col-lg-4">
    <?php if($this->config->item('cloudfiles')):
        $path=$this->cloud_files->url_publica("files/{$p->tabla}/{$p->elementos_id}/{$p->img_id}.jpg"); ?>
        <img src="<?php echo  site_url('/thumbs/timthumb.php?src='.$path.'&mw=400&mh=500&t='.time());?>" class="img-thumbnail center-block" style="height: 300px; width: auto;">
    <?php else: ?>
        <img class="img-thumbnail center-block" style="height: 300px; width: auto;" src="<?php echo site_url("/thumbs/timthumb.php?src=files/{$p->tabla}/{$p->elementos_id}/{$p->img_id}.jpg&mw=400&mh=500&t=".time());?>" />
    <?php endif;?>
</div>
<div class="col-lg-8">
    <div class="mb20">
        <h3 class="producto"><?php echo $p->nombre;?></h3>
    </div>
    <div class="estrellas-container">
        <!--    cambiar el data-table a dinamico    -->
        <div id="<?php echo $p->id?>" class="rate_widget" data-table="<?php echo $p->tabla?>">
            <div class="ratings_stars medianas" data-value="1"></div>
            <div class="ratings_stars medianas" data-value="2"></div>
            <div class="ratings_stars medianas" data-value="3"></div>
            <div class="ratings_stars medianas" data-value="4"></div>
            <div class="ratings_stars medianas" data-value="5"></div>
            <!--                                    <div class="total_votes">vote data</div>-->
        </div>
    </div>
    <div class=" p5">
        <textarea placeholder="Apreciamos sus comentarios" class="form-control <?php echo $p->id?>" rows="5"></textarea>
    </div>

</div>