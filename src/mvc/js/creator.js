/**
 * Created by BBN on 17/07/2017.
 */
(function(){
  return {
    props: ["source"],
    data(){
      var vm = this;
      return {
        linkedTables: [],
        id_table: vm.source.id_table ? vm.source.id_table : null,
        tables: []
      };
    },
    watch: {
      id_table(newVal, oldVal){
        var vm = this;
        if ( !newVal ){
          vm.linkedTables = [];
        }
        else{
          this.post(vm.source.root + 'change_table', {table: newVal}, function(d){
            vm.linkedTables = d.success ? d.tables : [];
          });
        }
      }
    },
  };
})();