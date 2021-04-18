// Javascript Document
(() => {
  "use strict";
  return {
    created(){
      let mixins = [{
        data(){
          return {
            table: null,
            isMobile: bbn.fn.isMobile()
          }
        },
        beforeMount(){
          this.table = this.closest('appui-database-table');
        }
      }];

      bbn.vue.addPrefix('appui-database-table-', (tag, resolve, reject) => {
        return bbn.vue.queueComponent(
          tag,
          'components/table/' + bbn.fn.replaceAll('-', '/', tag).substr('appui-database-table-'.length),
          mixins,
          resolve,
          reject
        );
      });
    },
    data(){
      return {
        orientation: "horizontal",
        root: appui.databases.source.root,
        option: this.source.option,
        cfg: {
          engine: this.source.engine,
          host: this.source.host,
          db: this.source.db,
          table: this.source.table
        }
      };
    },
    computed: {
      columns(){
        
      },
      //indexs in soure.constraints
      menu(){
        return [{
          text: '<i class="nf nf-fa-file"></i>'
        },{
          text: 'Structure'
        }, {
            text: 'Data'
          }, {
            text: 'Operation'
          }]
      }
    },
    methods:{
      save(type, value, oldValue) {
        bbn.fn.post(
          this.root + 'actions/table/set',
          bbn.fn.extend({
            type: type,
            value: value
          }, this.cfg)
        ).then(d => {
          if (!d.succes) {
            appui.error(bbn._("Impossible to update the option"));
            if (this.source.option[type] !== undefined) {
              this.$set(this.source.option, type, oldValue);
            }
          }
          else {
            appui.success(bbn._("Option updated successfully"));
          }
        })
      },
      format(n){
        return bbn.fn.money(n, false, '');
      },
      //table simple name for the render of constraints and externals
      tableSn(st){
        if ( st.length ){
          return st.split('.')[1];
        }
      },
      getStateColor(row){
        let col = false;
        if ( !row.is_real ){
          col = 'red';
        }
        else if ( !row.is_virtual ){
          col = 'purple';
        }
        else if ( row.is_same ){
          col = 'green';
        }
        return col;
      },
      /*writeNull(row){
        return row.null ? '<i class="nf nf-fa-check"> </i>' : ' ';
      },*/
      /*writeType(row){
        if ( row.type === 'int' ){
          row.type += ' (<em>' + ( row.signed ? '' : 'un') + 'signed)</em>';
        }
        return row.type;
      },*/
      /*writeColumn(row){
        let col = this.getStateColor(row);
        return '<a' + (col ? ' class="bbn-' + col + '"' : '') + '>' + row.name + '</a>';
      },*/
      /*writeKey(row){
        let col = this.getStateColor(row);
        return '<a' + (col ? ' class="bbn-' + col + '"' : '') + '>' + row.name + '</a>';
      },*/
      /*writeColInKey(row){
        return row.columns.join(", ");
      },*/
      /*writeKeyInCol(row){
        if ( !row.key ){
          return ' ';
        }
        return '<i class="nf nf-fa-key ' + (row.key === 'PRI' ? 'bbn-yellow' : 'bbn-grey') + '"> </i>';
      },*/
      /*writeDefault(row){
        return row.default_value || '-';
      },*/
      buttons(name){
        return '<bbn-button text="' + bbn._('Refresh whole structure in database') + '" @click="action(\'refresh\')" :notext="true" icon="zmdi zmdi-refresh-sync"></bbn-button> ' +
          '<a href="' + this.source.root + 'tabs/db/' + this.source.host + '/' + name + '"><bbn-button text="' + bbn._('View tables') + '" :notext="true" icon="nf nf-fa-eye"></bbn-button></a>';
      }
    },
    mounted(){
      this.$nextTick(() => {
        bbn.vue.closest(this, "bbn-container").addMenu({
          text: bbn._('Change orientation'),
          icon: 'nf nf-fa-compass',
          click(a){
            this.orientation = this.source.orientation === 'horizontal' ? 'vertical' : 'horizontal';
          }
        })
      });
    }
  }
})();
