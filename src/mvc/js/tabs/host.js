// Javascript Document
(() => {
  return {
    props: ['source'],
    methods: {
      viewButton(id, idx, data){
        
        return '<a href="' + this.source.root + 'tabs/db/' + data.code + '">' +
          '<bbn-button icon="nf nf-fa-eye" text="' + bbn._("View") + '" :notext="true"></bbn-button></a>';
      }
    },
  };
})()
