// Javascript Document
(() => {
  "use strict";
  return {
    props: {
      source: {
        type: Object
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
    statics(){
      let mixins = [{
        data(){
          return {
            main: null,
            isMobile: bbn.fn.isMobile()
          }
        },
        beforeMount(){
          this.main = this.closest('appui-database-table');
        }
      }];

      bbn.cp.addPrefix('appui-database-table-', null, mixins);
    },
    data(){
      return {
        orientation: "horizontal",
        root: appui.plugins['appui-databases'] + '/',
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
        currentData: this.source || null,
        ready: !!this.source,
        engines: {
          mysql: 'MySQL',
          mariadb: 'MariaDB',
          pgsql: 'PostgreSQL',
          sqlite: 'SQLite'
        },
        currentPageComponent: null
      };
    },
    computed: {
      toolbar(){
        let ar = [{
          icon: 'nf nf-md-refresh',
					text: bbn._("Refresh"),
          action: this.reload
        }];
        if (!this.currentData.is_virtual) {
          ar.push({
            icon: 'nf nf-md-opera',
            text: bbn._("Store as options"),
            action: this.dbToOption
          });
        }
        else {
          ar.push({
            icon: 'nf nf-md-opera',
            text: bbn._("Remove from options"),
            action: this.removeDbFromOption
          });
        }

        if (this.currentPageComponent?.mainMenu?.length) {
          ar.push({separator: true}, ...this.currentPageComponent.mainMenu);
        }

        return ar;
      },
      isHorizontal(){
        return this.orientation === 'horizontal';
      },
      currentInfo(){
        const list = [{
          text: bbn._("Table"),
          value: this.currentData.name
        }, {
          text: bbn._("Database"),
          value: this.currentData.database
        }, {
          text: bbn._("Engine"),
          value: this.engines[this.currentData.engine]
        }, {
          text: bbn._("Host"),
          value: this.currentData.host
        }];

        if (this.currentData.charset) {
          list.push({
            text: bbn._("Charset"),
            value: this.currentData.charset
          });
        }

        if (this.currentData.collation) {
          list.push({
            text: bbn._("Collation"),
            value: this.currentData.collation
          });
        }

        list.push({
          text: bbn._("Size"),
          value: this.formatBytes(this.currentData.size)
        }, {
          text: bbn._("No. Records"),
          value: this.currentData.count || 0
        }, {
          text: bbn._("No. Columns"),
          value: this.currentData.num_real_columns
        }, {
          text: bbn._("No. Keys"),
          value: this.currentData.num_real_keys
        }, {
          text: bbn._("No. Constraints"),
          value: this.currentData.num_real_constraints
        }, {
          text: bbn._("Options"),
          value: this.currentData.is_virtual ? bbn._("Yes") : bbn._("No")
        });

        if (this.currentData.is_virtual) {
          list.push({
            text: bbn._("No. Columns in options"),
            value: this.currentData.num_columns || 0
          }, {
            text: bbn._("No. Keys in options"),
            value: this.currentData.num_keys || 0
          }, {
            text: bbn._("No. Constraints in options"),
            value: this.currentData.num_constraints || 0
          });
        }

        return list;
      },
      option() {
        return this.source?.option || this.currentData?.option || {};
      },
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
      },
      fields() {
        const ar = [];
        bbn.fn.iterate(this.source.structure.fields, (f, name) => {
          ar.push(bbn.fn.extend({name}, f));
        });

        return bbn.fn.order(ar, 'position');
      }
    },
    methods:{
      formatBytes: bbn.fn.formatBytes,
      reload(){
        this.closest('bbn-container').reload()
      },
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
      save(type, value) {
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
            }
            else {
              this.currentData.option[type] = value;
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
      buttons(name){
        return '<bbn-button label="' + bbn._('Refresh whole structure in database') + '" @click="action(\'refresh\')" :notext="true" icon="zmdi zmdi-refresh-sync"></bbn-button> ' +
          '<a href="' + this.root + 'tabs/db/' + this.cfg.host + '/' + name + '"><bbn-button label="' + bbn._('View tables') + '" :notext="true" icon="nf nf-fa-eye"></bbn-button></a>';
      },
      onRouterRoute(url){
        this.$nextTick(() => {
          if (url) {
            const view = this.getRef('router').getView(url)
            if (view?.component) {
              const comp = this.find(view.component);
              if (comp) {
                this.currentPageComponent = comp;
                return;
              }
            }
          }
        });
        this.currentPageComponent = null;
      }
    },
    created() {
      if (!this.ready) {
        bbn.fn.post(appui.plugins['appui-database'] + '/data/table', this.cfg, d => {
          if (d.success) {
            this.currentData = d.data;
            this.ready = true;
          }
        });
      }
    },
    mounted() {
      this.$nextTick(() => {
        this.closest("bbn-container").addMenu({
          text: bbn._('Change orientation'),
          icon: 'nf nf-fa-compass',
          click(a){
            this.orientation = this.currentData?.orientation === 'horizontal' ? 'vertical' : 'horizontal';
          }
        })
      });
    }
  }
})();
