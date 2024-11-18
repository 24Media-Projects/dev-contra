
<?php 
/*
// Updated 06.12.2019
// to include campaign data id

<script type="text/javascript" src="https://promosimple.com/api/1.0/campaign/f000/iframe-loader"></script> 
*/ ?>


<script type="text/javascript">
  if(document.querySelector('a.promosimple')){
    document.querySelectorAll('a.promosimple').forEach(function(e,i){

      if(document.querySelector('promoSimple_' + i)) return;

      var promoSimple = e,
          promoSimpleCampaign = promoSimple.dataset.campaign;

      if(promoSimpleCampaign){
        var script = document.createElement('script');
        script.src = 'https://promosimple.com/api/1.0/campaign/'+ promoSimpleCampaign + '/iframe-loader';
        script.type = 'text/javascript';
        console.log(script);
        promoSimple.parentNode.appendChild(script);
        promoSimple.parentNode.id = 'promoSimple_' + i;

      }


    });

  }

</script>
