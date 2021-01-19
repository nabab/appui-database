/**
 * Created by BBN Solutions.
 * User: Loredana Bruno
 * Date: 14/02/17
 * Time: 16.32
 */

/** @var Object bbn */

(function(){
  return function(ele, data){
    var hostFormCmp = {
      props: ['value'],
      template: '#tpl-bbn-databases-desc-host',
      methods: {}
    },

    databaseFormCmp = {
      props: ['value'],
      template: '#tpl-bbn-databases-desc-database',
      methods: {
        create: function(){

        },
        update: function(){
          var vm = this;
          this.post(data.root + 'actions/database/update', {id: this.value.id}, function(d){
            if ( d.success ){
              appui.success((
                  d.success.tables ?
                    (d.success.tables > 1 ?
                        d.success.tables + bbn._("tables updated") :
                        d.success.tables + bbn._("table updated")
                    ) : bbn._("No table updated")
                ) + '<br>' + (
                  d.success.columns ?
                    (d.success.columns > 1 ?
                        d.success.columns + bbn._("columns updated") :
                        d.success.columns + bbn._("column updated")
                    ) : bbn._("No column updated")
                ) + '<br>' + (
                  d.success.keys ?
                    (d.success.keys > 1 ?
                        d.success.keys + bbn._("keys updated") :
                        d.success.keys + bbn._("key updated")
                    ) : bbn._("No key updated")
                ));
              vm.value.in_db = true;
            }
          })
        },
        remove: function(){
          var vm = this;
          appui.confirm(bbn._("All the objects associated with this database will be also deleted. None of the data will be affected. Are you sure you widh to proceed?"), function(){
            this.post(data.root + 'actions/database/remove', {id: vm.value.id}, function(d){
              if ( d.success ){
                vm.value.in_db = false;
                appui.success(bbn._("Database successfully removed from internal data"));
              }
            })
          })
        },
        search: function(){

        },
        analyze: function(){

        },
        importation: function(){

        },
        exportation: function(){

        },
        migration: function(){

        },
        deletion: function(){

        },

      },
    },

    tableFormCmp = {
      props: ['value'],
      template: '#tpl-bbn-databases-desc-table',
      methods: {
        create: function(){

        },
        update: function(){
          var vm = this;
          this.post(data.root + 'actions/table/update', {id: this.value.id}, function(d){
            if ( d.success ){
              appui.success((
                d.success.columns ?
                  (d.success.columns > 1 ?
                      d.success.columns + ' ' + bbn._("columns updated") :
                      d.success.columns + ' ' + bbn._("column updated")
                  ) : bbn._("No column updated")
              ) + '<br>' + (
                d.success.keys ?
                  (d.success.keys > 1 ?
                      d.success.keys + ' ' + bbn._("keys updated") :
                      d.success.keys + ' ' + bbn._("key updated")
                  ) : bbn._("No key updated")
              ));
              vm.value.in_db = true;
            }
          })
        },
        remove: function(){
          var vm = this;
          appui.confirm(bbn._("All the objects associated with this table will be also deleted. None of the data will be affected. Are you sure you widh to proceed?"), function(){
            this.post(data.root + 'actions/table/remove', {id: vm.value.id}, function(d){
              if ( d.success ){
                vm.value.in_db = false;
                appui.success(bbn._("Table successfully removed from internal data"));
              }
            })
          })
        },
        search: function(){

        },
        analyze: function(){

        },
        importation: function(){

        },
        exportation: function(){

        },
        migration: function(){

        },
        deletion: function(){

        },
      },
      computed: {
        creation: function(){
          if ( this.value && this.value.creation ){
            return bbn.fn.fdate(this.value.creation);
          }
          return '-';
        }
      }
    },

    columnFormCmp = {
      props: {
        value: {
          type: Object,
          default: function(){
            return {
              randomData: []
            };
          }
        }
      },
      template: '#tpl-bbn-databases-desc-column',
      data: function(){
        return {
          currentId: false,
          currentRandom: 0,
          num_values: 0
        };
      },
      methods: {
        count_values: function(){
          var vm = this;
          if ( vm.value ){
            this.post(data.root + 'actions/column/count', {id: vm.value.id}, function(d){
              if ( d && d.success ){
                vm.num_values = d.success;
              }
            })
          }
        },
        nextRandom: function(){
          this.currentRandom++;
          if ( this.value && this.value.randomData && (this.value.randomData[this.currentRandom] === undefined) ){
            this.currentRandom = 0;
          }
        },
        create: function(){

        },
        update: function(){

        },
        search: function(){

        },
        analyze: function(){

        },
        importation: function(){

        },
        exportation: function(){

        },
        migration: function(){

        },
        deletion: function(){

        },
      },
      computed: {
        display: function(){
          if ( this.value.randomData && this.value.randomData.length ){
            return this.value.randomData[this.currentRandom];
          }
          return '';
        },
        editor: function(){
          return 'bbn-input';
        },
      },
      watch: {
        value: function(val){
          if ( !this.currentId ){
            if ( val && val.id ){
              this.currentId = val.id;
            }
          }
          else{
            if ( !val || (val.id !== this.currentId) ){
              this.num_values = 0;
            }
          }
        }
      }

    },

    keyFormCmp = {
      props: ['value'],
      template: '#tpl-bbn-databases-desc-key',
      methods: {
      },

    },

    x = new Vue({
      el: $(ele).find("div:first")[0],
      components: {
        'host-form': hostFormCmp,
        'database-form': databaseFormCmp,
        'table-form': tableFormCmp,
        'column-form': columnFormCmp,
        'key-form': keyFormCmp,
      },
      data: {
        root: data.root,
        currentID: false,
        currentTitle: '',
        description: '',
        oldName: '',
        oldTitle: '',
        search: '',
        renaming: false,
        retitling: false
      },
      methods: {
        update_structure: function(){
          var vm = this;
          if ( vm.description ){
            this.post(data.root + 'data/generate/' + vm.description.name, function(d){
              if ( d ){
                if ( !vm.description.in_db ){
                  vm.description.in_db = true;
                }
                if ( d.num && (d.num !== vm.description.num_tables) ){
                  vm.description.num_tables = d.num;
                }
              }
            })
          }
        },
        pretitle: function(){
          if ( this.description ){
            this.oldTitle = this.description.title || '';
          }
        },
        retitle: function(){
          var vm = this;
          if ( vm.description && (vm.currentTitle != vm.oldTitle) ){
            this.post(data.root + 'actions/title', {id: vm.description.id, text: vm.currentTitle}, function(d){
              if ( !d.success ){
                vm.currentTitle = vm.oldTitle;
                var node = vm.$refs.tree.widget.getNodeByKey(vm.description.node);
                node.setTitle(vm.currentTitle);
              }
              else{
                vm.oldTitle = vm.currentTitle;
              }
            })
          }
        },
        rename: function(){
          var vm = this;
          if ( vm.oldName !== vm.description.name ){
            appui.confirm("Are you sure of your modification?", function(){
              vm.oldName = vm.description.name;
              vm.renaming = false;
              var node = vm.$refs.tree.widget.getNodeByKey(vm.description.node);
              node.setTitle(vm.description.name);
              alert("TO DO!");
            }, function(){
              vm.description.name = vm.oldName;
            })
          }
          vm.renaming = false;
        },
        init: function () {
          bbn.fn.analyzeContent($(ele.children[0]), true);
          $("div.tree-db").bbn('redraw', true);
          $("div.formulary-db").bbn('redraw', true);
        },
        get_description: function(id, dt, node){
          var vm = this,
              bits = id.split(".");
          if ( (bits.length === 3) && ((bits[2] == 1) || (bits[2] == 2)) ){
            return;
          }
          vm.renaming = false;
          this.post(data.root + 'desc', {name: id, node: node.key}, function(d){
            vm.description = d;
            vm.currentTitle = d.title || '';
            vm.$nextTick(function(){
              bbn.fn.analyzeContent(vm.$el, true);
            })
          });
        },
      },
      computed: {
        descriptionJSON: function(){
          return this.description ? JSON.stringify(this.description, true, 2) : '?';
        },
        descriptionIcon: function(){
          if ( this.description && this.description.structure ){
            return 'nf nf-fa-' + this.description.structure + ( this.description.structure === 'column' ? 's' : '' );
          }
          return '';
        }
      },
      mounted: function(){
        this.init();
      },
      watch: {
        renaming: function(newVal){
          if ( newVal ){
            this.$nextTick(function(){
              $(this.$el).find("input[name=title]").focus();
            })
          }
        },
        search: function(newVal){
          var mv = this,
              tree = mv.$refs.tree.widget;
          if ( !newVal.length ){
            tree.clearFilter();
          }
          else{
            tree.filterNodes(function(a){
              var bits = newVal.split(" ");
              for ( var i = 0; i < bits.length; i++ ){
                if ( bits[i] && !(a.data.id.indexOf(bits[i]) > -1) ){
                  return false;
                }
              }
              return true;
            });
          }
        },
      }
    });
  }
})();