( function( $ ){

  var BTWGroupFields = function(){

    this.templateMapging = BTWGF.template_mapging;
    this.$groupTemplate = $( '#acf-field_63f5d885f740c' );
    this.$groupBonTemplate = $( '#acf-field_5db6fea310c4e' );
    this.$groupType = $( '#acf-field_63f89669cc196' );
    this.acfContainersSelector = '.acf-postbox';

    this.templateMapging.forEach( function( template, index ){
      template.container = document.querySelector( '#acf-' + template.acf_group_field_key );
    });

    this.activeTemplate = null;

    console.log(this.templateMapging);

    this.init();
  }

  BTWGroupFields.prototype.init = function(){

    // this.setActiveTemplate();
    this._bindEvents();

  }

  BTWGroupFields.prototype.getMatchedTemplate = function(){

    var self = this,
        activeTemplate = this.templateMapging.filter( function( template ){
          return ( template.template_slug === self.$groupTemplate.val() ||  template.template_slug === self.$groupBonTemplate.val() ) && template.group_type === self.$groupType.val();
        }).map( function( template ){
          return template.container;
        }).shift();

    return activeTemplate ?? null;
  }


  BTWGroupFields.prototype.setActiveTemplate = function(){

    this.activeTemplate = this.getMatchedTemplate();

    if( this.activeTemplate ){
      this.activeTemplate.classList.add( 'btw-group-visible' );
    }

  }

  BTWGroupFields.prototype.clearActiveTemplate = function(){

    if( this.activeTemplate ){
      this.activeTemplate.classList.remove( 'btw-group-visible' );
      this.activeTemplate = null;
    }

  }



  BTWGroupFields.prototype._bindEvents = function(){
    // this.$groupTemplate.on( 'select2:select', this.onGroupTemplateChange.bind( this ) );
    // this.$groupBonTemplate.on( 'select2:select', this.onGroupTemplateChange.bind( this ) );
    // this.$groupType.on( 'select2:select', this.onGroupTemplateChange.bind( this ) );

    this.$groupTemplate.on( 'change', this.onGroupTemplateChange.bind( this ) );
    this.$groupBonTemplate.on( 'change', this.onGroupTemplateChange.bind( this ) );
    this.$groupType.on( 'change', this.onGroupTemplateChange.bind( this ) );

  }


  BTWGroupFields.prototype.onGroupTemplateChange = function( event ){
    this.clearActiveTemplate();
    this.setActiveTemplate();
  }



  var btwGroupFields = new BTWGroupFields();

})( jQuery );
