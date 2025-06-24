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
        force: false,
        hasMultipleSelected: false,
        currentData: this.source,
        ready: !!this.source,
        engines: {
          mysql: 'MySQL',
          mariadb: 'MariaDB',
          pgsql: 'PostgreSQL',
          sqlite: 'SQLite'
        },
        hasCollation: this.source?.engine !== 'sqlite',
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
            icon: 'nf nf-md-flask',
            label: bbn._("Analyze"),
            action: this.analyzeDb
          }, {
            icon: 'nf nf-md-trash_can',
            label: bbn._("Drop"),
            action: () => this.dropDb(this.getRef('table').currentSelected)
          }, {
            icon: 'nf nf-md-database_export',
            label: bbn._("Export"),
            action: this.exportDb
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
      }
    },
    methods:{
      formatBytes: bbn.fn.formatBytes,
      getTableButtons(row){
        return row.is_real ? [{
          text: bbn._("Analyze"),
          action: this.analyzeDb,
          icon: 'nf nf-md-flask',
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
          text: bbn._("Import"),
          action: this.importDb,
          icon: 'nf nf-md-database_import',
        }, {
          text: bbn._("Export"),
          action: this.exportDb,
          icon: 'nf nf-md-database_export',
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
            host: this.source.id,
            engine: this.source.engine,
            charset: this.source.charset || '',
            collation: this.source.collation || ''

          }
        })
      },
      analyzeDb(db) {
        if (!bbn.fn.isString(db)) {
          db = this.getRef('table').currentSelected;
        }

        bbn.fn.post(this.root + 'actions/database/analyze', {
          host_id: this.source.id,
          db: db
        }, d => {
          if (d.success) {
          }
        });
      },
      duplicateDb(db) {
        if (this.source?.id && db?.name?.length) {
          this.getPopup({
            label: bbn._("Duplicate database"),
            component: 'appui-database-db-duplicate',
            componentOptions: {
              host: this.source.id,
              database: db.name,
              options: !!db.is_virtual
            },
            componentEvents: {
                success: () => {
                  this.getRef('table').updateData();
                }
              }
          });
        }
      },
      renameDb(db) {
        if (this.source?.id && db?.name?.length) {
          this.getPopup({
            label: bbn._("Rename database"),
            component: 'appui-database-db-rename',
            componentOptions: {
              host: this.source.id,
              database: db.name,
              options: !!db.is_virtual
            },
            componentEvents: {
                success: () => {
                  this.getRef('table').updateData();
                }
              }
          });
        }
      },
      dropDb(db){
        let options = false;
        let database = '';
        if (bbn.fn.isArray(db)) {
          database = db;
          options = !!bbn.fn.filter(db, d => {
            return !!bbn.fn.getField(
              this.getRef('table').currentData,
              'data',
              'data.name',
              d.name || d
            ).is_virtual;
          }).length;
        }
        else {
          database = db.name;
          options = !!db.is_virtual;
        }

        if (this.source?.id && database.length) {
          this.getPopup({
            label: false,
            component: 'appui-database-db-drop',
            componentOptions: {
              host: this.source.id,
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
      importDb(db){},
      exportDb(db){},
      toOption(db){
        if (!bbn.fn.isString(db)) {
          db = this.getRef('table').currentSelected;
        }
        bbn.fn.post(this.root + 'actions/database/options', {
          host_id: this.source.id,
          db: db
        }, d => {
          if (d.success) {
            appui.success();
          }
          else {
            appui.error();
          }
        });
      },
      exportDb(){
        //this.closest('bbn-router').route('host/export')
        bbn.fn.link(this.root + 'tabs/' + this.source.engine + '/' + this.source.id + '/export');
      },
      getStateColor(row){
        let col = false;
        if ( row.num_tables !== row.num_real_tables ){
          col = 'orange';
          if ( !row.is_real ){
            col = 'red';
          }
          else if ( !row.is_virtual ){
            col = 'purple';
          }
          else if ( row.is_same ){
            col = 'green';
          }
        }
        return col;
      },
      writeDB(row){
        let col = this.getStateColor(row);
        return '<a href="' + this.root + 'tabs/' + this.source.engine + '/' + this.source.id + '/' + row.name + '" class="bbn-b' +
          (col ? ' bbn-' + col : '') +
          '">' + row.name + '</a>';
      },
      refresh(row){
        bbn.fn.log(',----',row, arguments, 'fine')
        //appui.confirm(bbn._('Are you sure you want to import|refresh this database?'), () => {
          this.post(this.root + 'actions/database/update', {
            engine: this.source.engine,
            host: this.source.id,
            db: row.name
          }, (d) => {
            if ( d.success ){
              this.$refs.table.updateData();
            }
            bbn.fn.log("RESULT", this.$refs.table, d);
          });
          bbn.fn.log("refresh", arguments);
      //  });
      },
      onTableToggle(){
        this.hasMultipleSelected = this.getRef('table')?.currentSelected?.length > 1;
      },
      renderRealVirtual(row, col){
        const icon = !!row[col.field] ? 'nf nf-fa-check bbn-green' : 'nf nf-fa-times bbn-red';
        return '<i class="' + icon + '"></i>';
      }
    },
    mounted(){
      if (!this.ready) {
        bbn.fn.post(appui.plugins['appui-database'] + '/data/host', {engine: this.engine, host: this.host}, d => {
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
  }
})();
