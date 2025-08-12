// Javascript Document
(() => {
  return {
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
      }
    },
    data(){
      const root = appui.plugins['appui-database'] + '/';
      return {
        orientation: 'horizontal',
        root,
        hasSelected: false,
        currentData: this.source,
        ready: !!this.source,
        engines: {
          mysql: 'MySQL',
          mariadb: 'MariaDB',
          pgsql: 'PostgreSQL',
          sqlite: 'SQLite'
        }
      };
    },
    computed: {
      toolbar(){
        let ar = [{
          icon: 'nf nf-md-refresh',
					text: bbn._("Refresh"),
          action: () => {
            this.closest('bbn-container').reload()
          }
        }, {
          icon: 'nf nf-md-table_plus',
					text: bbn._("Create"),
          action: this.createTable
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

        if (this.hasSelected) {
          const table = this.getRef('table');
          const currentSelected = table?.currentSelected || [];
          const currentSelectedVirtual = bbn.fn.filter(
            currentSelected,
            d => !!bbn.fn.getField(table?.currentData || [], 'data', 'data.name', d)?.is_virtual
          );
          const currentSelectedNoVirtual = bbn.fn.filter(
            currentSelected,
            d => !bbn.fn.getField(table?.currentData || [], 'data', 'data.name', d)?.is_virtual
          );
          ar.push({
            separator: true
          }, {
            icon: 'nf nf-md-table_refresh',
            text: bbn._("Refresh"),
            action: () => this.refreshTable(currentSelected)
          }, {
            icon: 'nf nf-md-trash_can',
            text: bbn._("Drop"),
            action: () => this.dropTable(currentSelected)
          }, {
            icon: 'nf nf-md-flask',
            text: bbn._("Analyze"),
            action: () => this.analyzeTable(currentSelected)
          }, {
            icon: 'nf nf-md-database_export',
            text: bbn._("Export"),
            action: () => this.exportTable(currentSelected),
            disabled: true
          }, {
            icon: 'nf nf-md-opera',
            text: bbn._("Options"),
            items: [{
              icon: 'nf nf-md-opera',
              text: currentSelectedVirtual.length ?
                (!currentSelectedNoVirtual.length ? bbn._("Update structure") : bbn._("Store or update structure")) :
                bbn._("Store structure"),
              action: () => this.toOption(currentSelected)
            }]
          });
          if (currentSelectedVirtual.length) {
            ar[ar.length - 1].items.push({
              icon: 'nf nf-md-opera',
              text: bbn._("Remove"),
              action: () => this.removeFromOption(currentSelectedVirtual)
            });
          }
        }

        return ar;
      },
      isHorizontal(){
        return this.orientation === 'horizontal';
      },
      currentInfo(){
        const list = [{
          text: bbn._("Database"),
          value: this.currentData.name
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
          text: bbn._("No. Tables"),
          value: this.currentData.num_real_tables
        }, {
          text: bbn._("No. Functions"),
          value: this.currentData.num_real_functions
        }, {
          text: bbn._("No. Procedures"),
          value: this.currentData.num_real_procedures
        }, {
          text: bbn._("Options"),
          value: this.currentData.is_virtual ? bbn._("Yes") : bbn._("No")
        });

        if (this.currentData.is_virtual) {
          list.push({
            text: bbn._("No. tables in options"),
            value: this.currentData.num_tables || 0
          }, {
            text: bbn._("No. functions in options"),
            value: this.currentData.num_functions || 0
          }, {
            text: bbn._("No. procedures in options"),
            value: this.currentData.num_procedures || 0
          }, {
            text: bbn._("No. connections in options"),
            value: this.currentData.num_connections || 0
          });
        }

        return list;
      },
      hasCollation(){
        return this.currentData?.engine !== 'sqlite';
      }
    },
    methods:{
      formatBytes: bbn.fn.formatBytes,
      getTableButtons(row){
        const btns = [];
        if (row.is_real) {
          btns.push({
            text: bbn._("Open"),
            action: () => {
              bbn.fn.link(appui.plugins['appui-database'] + '/tabs/' + this.currentData.engine + '/' + this.currentData.id_host + '/' + this.currentData.name + '/' + row.name + '/home')
            },
            icon: 'nf nf-md-open_in_app',
          }, {
            text: bbn._("Refresh"),
            action: this.refreshTable,
            icon: 'nf nf-md-table_refresh',
          }, {
            text: bbn._("Duplicate"),
            action: this.duplicateTable,
            icon: 'nf nf-md-content_copy',
          }, {
            text: bbn._("Rename"),
            action: this.renameTable,
            icon: 'nf nf-md-square_edit_outline',
          }, {
            text: bbn._("Drop"),
            action: this.dropTable,
            icon: 'nf nf-md-trash_can',
          }, {
            text: bbn._("Maintenance"),
            icon: 'nf nf-fa-screwdriver_wrench',
            items: [{
              text: bbn._("Analyze"),
              action: () => this.analyzeTable(row),
              icon: 'nf nf-md-flask',
            }]
          }, {
            text: bbn._("Import"),
            action: this.importTable,
            icon: 'nf nf-md-database_import',
            disabled: true,
          }, {
            text: bbn._("Export"),
            action: this.exportTable,
            icon: 'nf nf-md-database_export',
            disabled: true
          });
        }

        if (row.is_virtual) {
          btns.push({
            text: bbn._("Options"),
            icon: 'nf nf-md-opera',
            items: [{
              text: bbn._("Update structure"),
              action: () => this.toOption(row),
              icon: 'nf nf-md-update',
            }, {
              text: bbn._("Remove"),
              action: () => this.removeFromOption(row),
              icon: 'nf nf-md-trash_can',
            }]
          });
        }
        else {
          btns.push({
            text: bbn._("Store structure"),
            action: this.toOption,
            icon: 'nf nf-md-opera',
          });
        }

        return btns;
      },
      createTable(){
        this.getPopup({
          label: bbn._("New table"),
          component: 'appui-database-table-create',
          data: this.cfg,
          //width: "120em",
          maxWidth: "95%",
          //height: "60em",
          maxHeight: "95%",
          source: {
            id_host: this.currentData.id_host,
            host: this.currentData.host,
            engine: this.currentData.engine,
            db: this.currentData.name,
            types: this.currentData.data_types,
            predefined: this.currentData.pcolumns,
            constraints: this.currentData.constraints,
            charsets: this.currentData.charsets,
            charset: this.currentData.charset,
            collations: this.currentData.collations,
            collation: this.currentData.collation,
            options: !!this.currentData.is_virtual
          }
        });
      },
      refreshTable(row) {
        this.post(this.root + 'actions/table/refresh', {
          host_id: this.currentData.id_host,
          db: this.currentData.name,
          table: bbn.fn.isArray(row) ? row : row.name
        }, d => {
          if (d.success && (d.data !== undefined)) {
            bbn.fn.iterate(d.data, (v, t) => {
              let r = bbn.fn.getRow(this.getRef('table').currentData, 'data.name', t);
              if (r) {
                bbn.fn.iterate(v, (val, k) => {
                  r.data[k] = val;
                });
              }
            });
            this.clearTableSelection();
            appui.success();
          }
          else {
            appui.error(d.error || bbn._('An error occurred'));
          }
        });
      },
      analyzeTable(row, row2) {
        this.post(this.root + 'actions/table/analyze', {
          host_id: this.currentData.id_host,
          db: this.currentData.name,
          table: bbn.fn.isArray(row) ? row : row.name
        }, d => {
          if (d.success) {
            this.clearTableSelection();
            appui.success();
          }
          else {
            appui.error(d.error || bbn._('An error occurred'));
          }
        });
      },
      duplicateTable(row) {
        if (this.currentData?.id_host
          && this.currentData?.name
          && row?.name?.length
        ) {
          this.getPopup({
            label: bbn._("Duplicate table"),
            component: 'appui-database-table-duplicate',
            componentOptions: {
              host: this.currentData.id_host,
              database: this.currentData.name,
              table: row.name,
              options: !!row.is_virtual
            },
            componentEvents: {
                success: () => {
                  this.getRef('table').updateData();
                }
              }
          });
        }
      },
      renameTable(row) {
        if (this.currentData?.id_host
          && this.currentData?.name
          && row?.name?.length
        ) {
          this.getPopup({
            label: bbn._("Rename table"),
            component: 'appui-database-table-rename',
            componentOptions: {
              host: this.currentData.id_host,
              database: this.currentData.name,
              table: row.name,
              options: !!row.is_virtual
            },
            componentEvents: {
                success: () => {
                  this.getRef('table').updateData();
                }
              }
          });
        }
      },
      dropTable(row){
        let options = false;
        let table = '';
        if (bbn.fn.isArray(row)) {
          table = row;
          options = !!bbn.fn.filter(row, d => {
            return !!bbn.fn.getField(
              this.getRef('table').currentData,
              'data',
              'data.name',
              d.name || d
            ).is_virtual;
          }).length;
        }
        else {
          table = row.name;
          options = !!row.is_virtual;
        }

        if (this.currentData?.id_host
          && this.currentData?.name
          && table.length
        ) {
          this.getPopup({
            label: false,
            component: 'appui-database-table-drop',
            componentOptions: {
              host: this.currentData.id_host,
              database: this.currentData.name,
              table,
              options
            },
            componentEvents: {
                success: () => {
                  this.clearTableSelection();
                  this.getRef('table').updateData();
                }
              }
          });
        }
      },
      toOption(row){
        let mess;
        let table;
        if (bbn.fn.isArray(row)) {
          table = bbn.fn.map(row, d => d.name || d);
          mess = bbn._(
            "Are you sure you want to store the structure of the tables %s as options?",
            bbn.fn.map(row, d => '"' + d + '"').join(", ")
          );
        }
        else {
          table = row.name;
          mess = bbn._("Are you sure you want to store the structure of the table \"%s\" as options?", table);
        }
        this.confirm(mess, () => {
          this.post(this.root + 'actions/table/options', {
            host_id: this.currentData.id_host,
            db: this.currentData.name,
            table
          }, d => {
            if (d.success) {
              appui.success();
              if (!this.currentData.is_virtual) {
                this.closest('bbn-container').reload()
              }
              else {
                this.clearTableSelection();
                this.getRef('table').updateData();
              }
            }
            else {
              appui.error();
            }
          }, () => {
            appui.error(bbn._('An error occurred'));
          });
        });
      },
      removeFromOption(row){
        let mess;
        let table;
        if (bbn.fn.isArray(row)) {
          table = bbn.fn.map(row, d => d.name || d);
          mess = bbn._(
            "Are you sure you want to remove the tables %s from options?",
            bbn.fn.map(row, d => '"' + d + '"').join(", ")
          );
        }
        else {
          table = row.name;
          mess = bbn._("Are you sure you want to remove the table \"%s\" from options?", table);
        }
        this.confirm(mess, () => {
          this.post(this.root + 'actions/table/options', {
            host_id: this.currentData.id_host,
            db: this.currentData.name,
            table,
            remove: true
          }, d => {
            if (d.success) {
              appui.success();
              this.clearTableSelection();
              this.getRef('table').updateData();
            }
            else {
              appui.error();
            }
          }, () => {
            appui.error(bbn._('An error occurred'));
          });
        });
      },
      dbToOption() {
        this.confirm(
          bbn._("Are you sure you want to store the structure of the database \"%s\" as options?", this.currentData.name),
          () => {
            this.post(this.root + 'actions/database/options', {
              host_id: this.currentData.id_host,
              db: this.currentData.name
            }, d => {
              if (d.success) {
                appui.success();
                this.closest('bbn-container').reload()
              }
              else {
                appui.error(d.error || bbn._('An error occurred'));
              }
            }, () => {
              appui.error(bbn._('An error occurred'));
            });
          }
        );
      },
      removeDbFromOption(){
        this.confirm(
          bbn._("Are you sure you want to remove the database \"%s\" from options?", this.currentData.name),
          () => {
            this.post(this.root + 'actions/database/options', {
              host_id: this.currentData.id_host,
              db: this.currentData.name,
              remove: true
            }, d => {
              if (d.success) {
                appui.success();
                this.closest('bbn-container').reload()
              }
              else {
                appui.error(d.error || bbn._('An error occurred'));
              }
            }, () => {
              appui.error(bbn._('An error occurred'));
            });
          }
        );
      },
      importTable(){},
      exportTable(){
        //this.closest('bbn-router').route('host/export')
        bbn.fn.link(this.root + 'tabs/' + this.currentData.engine + '/' + this.currentData.host + '/export');
      },
      onTableToggle(){
        this.hasSelected = !!this.getRef('table')?.currentSelected?.length;
      },
      clearTableSelection(){
        const table = this.getRef('table');
        if (table?.currentSelected?.length) {
          table.currentSelected.splice(0);
        }

        this.hasSelected = false;
      },
      renderRealVirtual(row, col){
        const icon = !!row[col.field] ? 'nf nf-fa-check bbn-green' : 'nf nf-fa-times bbn-red';
        return '<i class="' + icon + '"></i>';
      }
    },
    mounted() {
      if (!this.ready) {
        this.post(this.root + 'data/db', {
          engine: this.engine,
          host: this.host,
          db: this.database
        }, d => {
          if (d.success) {
            this.currentData = d.data;
            this.ready = true;
          }
        })
      }
      this.$nextTick(() => {
        this.closest("bbn-container").addMenu({
          text: bbn._('Change orientation'),
          icon: 'nf nf-fa-compass',
          action: () => {
            this.orientation = this.orientation === 'horizontal' ? 'vertical' : 'horizontal';
          }
        })
      });
    }
  };
})();
