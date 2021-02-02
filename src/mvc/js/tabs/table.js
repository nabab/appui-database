// Javascript Document
(() => {
  return {
    props: ['source'],
    methods: {
      viewButton(id, idx, data){
        bbn.fn.log("VIEW", arguments);
        return '<a href="' + this.source.root + 'tabs/tables/' + data.code + '">' +
          '<bbn-button icon="nf nf-fa-eye" text="' + bbn._("View") + '" :notext="true"></bbn-button></a>';
      }
    },
  };
})();
