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
      info: {},
      name: {}
    },
    data(){
      return {
        orientation: 'horizontal',
        root: appui.plugins['appui-database'] + '/',
        hasMultipleSelected: false,
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
					label: bbn._("Refresh"),
          action: () => {
            this.closest('bbn-container').reload()
          }
        }, {
          icon: 'nf nf-md-database_plus',
					label: bbn._("Create"),
          action: this.createDb
        }, {
          icon: 'nf nf-md-database_import',
					label: bbn._("Import"),
          action: this.importDb
        }];
        if (this.hasMultipleSelected) {
          ar.push({
            content: '<div class="bbn-toolbar-separator"/>'
          }, {
            icon: 'nf nf-md-database_refresh',
            label: bbn._("Refresh"),
            action: () => this.refreshDb(this.getRef('table')?.currentSelected || [])
          }, {
            icon: 'nf nf-md-trash_can',
            label: bbn._("Drop"),
            action: () => this.dropDb(this.getRef('table')?.currentSelected || [])
          }, {
            icon: 'nf nf-md-flask',
            label: bbn._("Analyze"),
            action: () => this.analyzeDb(this.getRef('table')?.currentSelected || [])
          }, {
            icon: 'nf nf-md-database_export',
            label: bbn._("Export"),
            action: this.exportDb,
            disabled: true
          });
        }

        return ar;
      },
      isHorizontal(){
        return this.orientation === 'horizontal';
      },
      currentInfo(){
        const list = [{
          text: bbn._("Engine"),
          value: this.engines[this.currentData.engine]
        }, {
          text: bbn._("Host"),
          value: this.currentData.name
        }];

        if (this.currentData.ip) {
          list.push({
            text: bbn._("IP"),
            value: this.currentData.ip
          });
        }

        if (this.currentData.user) {
          list.push({
            text: bbn._("User"),
            value: this.currentData.user
          });
        }

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

        if (this.currentData.version) {
          list.push({
            text: bbn._("Version"),
            value: this.currentData.version
          });
        }

        list.push({
          text: bbn._("Size"),
          value: this.formatBytes(this.currentData.size)
        });

        return list;
      },
      hasCollation(){
        return this.currentData?.engine !== 'sqlite';
      }
    },
    methods:{
      formatBytes: bbn.fn.formatBytes,
      getTableButtons(row){
        return row.is_real ? [{
          text: bbn._("Open"),
          action: () => {
            bbn.fn.link(appui.plugins['appui-database'] + '/tabs/' + this.currentData.engine + '/' + this.currentData.id + '/' + row.name + '/home')
          },
          icon: 'nf nf-md-open_in_app',
        }, {
          text: bbn._("Refresh"),
          action: this.refreshDb,
          icon: 'nf nf-md-database_refresh',
        }, {
          text: bbn._("Duplicate"),
          action: this.duplicateDb,
          icon: 'nf nf-md-content_copy',
        }, {
          text: bbn._("Rename"),
          action: this.renameDb,
          icon: 'nf nf-md-square_edit_outline',
        }, {
          text: bbn._("Drop"),
          action: this.dropDb,
          icon: 'nf nf-md-trash_can',
        }, {
          text: bbn._("Maintenance"),
          icon: 'nf nf-fa-screwdriver_wrench',
          items: [{
            text: bbn._("Analyze"),
            action: this.analyzeDb,
            icon: 'nf nf-md-flask',
          }]
        }, {
          text: bbn._("Import"),
          action: this.importDb,
          icon: 'nf nf-md-database_import',
          disabled: true,
        }, {
          text: bbn._("Export"),
          action: this.exportDb,
          icon: 'nf nf-md-database_export',
          disabled: true
        }, {
          text: row.is_virtual ? bbn._("Update structure in options") : bbn._("Store structure as options"),
          action: this.toOption,
          icon: 'nf nf-md-opera',
        }] : [];
      },
      createDb(){
        this.getPopup({
          label: bbn._("New database"),
          scrollable: true,
          component: 'appui-database-db-create',
          componentOptions: {
            host: this.currentData.id,
            engine: this.currentData.engine,
            charset: this.currentData.charset || '',
            collation: this.currentData.collation || ''

          }
        })
      },
      refreshDb(row){
        this.post(this.root + 'actions/database/refresh', {
          host_id: this.currentData.id,
          db: bbn.fn.isArray(row) ? row : row.name
        }, d => {
          if (d.success && (d.data !== undefined)) {
            bbn.fn.iterate(d.data, (v, db) => {
              let r = bbn.fn.getRow(this.getRef('table').currentData, 'data.name', db);
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
      analyzeDb(row){
        this.post(this.root + 'actions/database/analyze', {
          host_id: this.currentData.id,
          db: bbn.fn.isArray(row) ? row : row.name
        }, d => {
          if (d.success && (d.data !== undefined)) {
            bbn.fn.iterate(d.data, (v, db) => {
              let r = bbn.fn.getRow(this.getRef('table').currentData, 'data.name', db);
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
      duplicateDb(row) {
        if (this.currentData?.id && row?.name?.length) {
          this.getPopup({
            label: bbn._("Duplicate database"),
            component: 'appui-database-db-duplicate',
            componentOptions: {
              host: this.currentData.id,
              database: row.name,
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
      renameDb(row) {
        if (this.currentData?.id && row?.name?.length) {
          this.getPopup({
            label: bbn._("Rename database"),
            component: 'appui-database-db-rename',
            componentOptions: {
              host: this.currentData.id,
              database: row.name,
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
      dropDb(row){
        let options = false;
        let database = '';
        if (bbn.fn.isArray(row)) {
          database = row;
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
          database = row.name;
          options = !!row.is_virtual;
        }

        if (this.currentData?.id && database.length) {
          this.getPopup({
            label: false,
            component: 'appui-database-db-drop',
            componentOptions: {
              host: this.currentData.id,
              database,
              options
            },
            componentEvents: {
                success: () => {
                  this.getRef('table').updateData();
                }
              }
          });
        }
      },
      toOption(row){
        let mess;
        let db;
        if (bbn.fn.isArray(row)) {
          db = bbn.fn.map(row, d => d.name || d);
          mess = bbn._(
            "Are you sure you want to store the structure of the databases %s as options?",
            bbn.fn.map(row, d => '"' + d + '"').join(", ")
          );
        }
        else {
          db = row.name;
          mess = bbn._("Are you sure you want to store the structure of the database \"%s\" as options?", db);
        }
        this.confirm(mess, () => {
          this.post(this.root + 'actions/database/options', {
            host_id: this.currentData.id,
            db
          }, d => {
            if (d.success) {
              appui.success();
            }
            else {
              appui.error();
            }
          }, () => {
            appui.error(bbn._('An error occurred'));
          });
        });
      },
      importDb(){},
      exportDb(row){
        //this.closest('bbn-router').route('host/export')
        bbn.fn.link(this.root + 'tabs/' + this.currentData.engine + '/' + this.currentData.id + '/export');
      },
      onTableToggle(){
        this.hasMultipleSelected = this.getRef('table')?.currentSelected?.length > 1;
      },
      clearTableSelection(){
        const table = this.getRef('table');
        if (table?.currentSelected?.length) {
          table.currentSelected.splice(0);
        }
      },
      renderRealVirtual(row, col){
        const icon = !!row[col.field] ? 'nf nf-fa-check bbn-green' : 'nf nf-fa-times bbn-red';
        return '<i class="' + icon + '"></i>';
      }
    },
    mounted(){
      if (!this.ready) {
        this.post(this.root + 'data/host', {
          engine: this.engine,
          host: this.host
        }, d => {
          if (d.success) {
            this.currentData = d.data;
            this.ready = true;
          }
        });
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
  }
})();
