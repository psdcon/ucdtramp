		        

		        <div class="clearfix"></div> <!--clearfix-->
        	</div> <!-- padded-content -->
        </div> <!-- content -->
    </div>  <!-- bg-container -->

    <footer>
        <picture>
            <source media="(min-width:769px)" srcset="images/backgrounds/footer.jpg">
            <!-- <img src="images/backgrounds/mfooter.jpg" alt=""> -->
            <img onload="if(window.innerWidth>768)this.src='images/backgrounds/footer.jpg'" src="images/backgrounds/mfooter.jpg" alt="Bottom of trampoline">
        </picture>

        <div class="footer__links">
            <!--Inside containing divs so that the link description text can be added via css and hence can change per device-->
           <!--  <a href="https://www.facebook.com/UCDTC" target="_blank" title="Our Facebook page">
                <img src="//ucdtramp.com/images/msc/facebook.png" alt="Facebook Icon"><br><div id="fb"></div></a>
            <a href="http://www.youtube.com/user/ucdtramp" target="_blank" title="Our YouTube Channel">
                <img src="//ucdtramp.com/images/msc/youtube.png" alt="Youtube Icon"><br><div id="youtube"></div></a>
            <a href="//ucdtramp.com/page/constitution" target="_blank" title="View the image full size">
                <img src="//ucdtramp.com/images/msc/constitution.png" alt="Club constitution"><br><div id="const"></div></a> -->
        </div>
		<!-- <div id="bighead">Site designed and built by Paul Connolly</div> -->
    </footer>
    
    <!-- Non blocking css -->
    <link href="dist/css/animate.css" rel="stylesheet">
    <link href="dist/css/emojione.sprites.css" rel="stylesheet">
    <!--Font awesome - icon plugin -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="dist/js/bootstrap.min.js"></script>
    <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script> -->
    <script src="dist/js/main.js"></script>
    
    <!-- Bootstrap error checking -->
    <!-- <script>(function(){var s=document.createElement("script");s.onload=function(){bootlint.showLintReportForCurrentDocument([]);};s.src="https://maxcdn.bootstrapcdn.com/bootlint/latest/bootlint.min.js";document.body.appendChild(s)})();</script> -->


    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-41915009-3', 'ucdtramp.com');
      ga('send', 'pageview');
    </script>
</body>
</html>

<?php
mysqli_close($db);
?>