<?php
get_header();

?>
<section id="title">
    <div class="container">
        <div class="row">
            <div class="col-md-9 col-sm-8">
                <div class="pagetitle">
                    <h1> Page Not Found !!!</h1>

                </div>
            </div>
            <div class="col-md-3 col-sm-4">
                <?php
                        vibe_breadcrumbs(); 
                ?>
            </div>
        </div>
    </div>
</section>

<section id="content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <div class="content">
                    <div class="page_not_found center">
                        <h2>404 ERROR</h2>
                        
                        <p> We are sorry but the page you are looking for could not be found,<br>
                        
                        please try again.</p>                                
                        
                          
                         
                          <p class="go_back"><a href="<?php echo site_url();?>">Click here to go back to Home</a></p>
                      </div>
                </div>
            </div>
        </div>
    </div>
</section>

</div>

<?php
get_footer();
?>