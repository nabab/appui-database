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
    props: {
      source: {
        type: Object,
      },
      engine: {
        type: String
      },
      host: {
        type: String
      },
      database: {
        type: String
      },
      table: {
        type: String
      }
    },
    data(){
      return {
        orientation: "horizontal",
        root: appui.plugins['appui-databases'] + '/',
        option: this.source?.option,
        cfg: this.source ? {
          engine: this.source.engine,
          host: this.source.host,
          db: this.source.db,
          table: this.source.table
        } : {
          engine: this.engine,
          host: this.host,
          db: this.database,
          table: this.table
        },
        currentData: this.source,
        ready: !!this.source,
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
        }];
      }
    },
    methods:{
      rename(newName) {
        bbn.fn.post(
          this.root + 'actions/table/rename',
          bbn.fn.extend({
            name: newName
          }, this.cfg),
          d => {
            if (!d.succes) {
              appui.error(bbn._("Impossible to update the option"));
              if (this.currentData.option[type] !== undefined) {
                this.$set(this.currentData.option, type, oldValue);
              }
            }
            else {
              appui.success(bbn._("Option updated successfully"));
            }
          }
        );
      },
      save(type, value, oldValue) {
        bbn.fn.post(
          appui.plugins['appui-database'] + '/actions/table/set',
          bbn.fn.extend({
            type: type,
            value: value
          }, this.cfg),
          d => {
            bbn.fn.log("SAVE", d, arguments);
            if (!d.success) {
              appui.error(bbn._("Impossible to update the option"));
              if (this.currentData.option[type] !== undefined) {
                this.$set(this.currentData.option, type, oldValue);
              }
            }
            else {
              appui.success(bbn._("Option updated successfully"));
            }
          }
        );
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
          '<a href="' + this.currentData.root + 'tabs/db/' + this.currentData.host + '/' + name + '"><bbn-button text="' + bbn._('View tables') + '" :notext="true" icon="nf nf-fa-eye"></bbn-button></a>';
      }
    },
    mounted() {
      if (!this.ready) {
        bbn.fn.post(appui.plugins['appui-database'] + '/data/table', this.cfg, d => {
          if (d.success) {
            this.currentData = d.data;
            this.ready = true;
          }
        });
      }
      this.$nextTick(() => {
        bbn.vue.closest(this, "bbn-container").addMenu({
          text: bbn._('Change orientation'),
          icon: 'nf nf-fa-compass',
          click(a){
            this.orientation = this.currentData.orientation === 'horizontal' ? 'vertical' : 'horizontal';
          }
        })
      });
    }
  }
})();
