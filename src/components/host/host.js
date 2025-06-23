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
          icon: 'nf nf-md-database_plus',
					label: bbn._("Create database"),
          action: this.createDb
        }, {
          icon: 'nf nf-md-refresh',
					label: bbn._("Refresh host"),
          action: () => {
            this.closest('bbn-container').reload()
          }
        }];
        if (this.hasMultipleSelected) {
          ar.push({
            content: '<span>' + bbn._("With selected") + '</span>'
          }, {
            component: 'bbn-dropdown',
            options: {
              placeholder: bbn._("Choose an action on multiple databases"),
              source: [{
                text: bbn._("Drop"),
                action: this.drop
              }, {
                text: bbn._("Analyze"),
                action: this.analyze
              }]
            }
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
          action: this.analyze,
          icon: 'nf nf-md-flask',
        }, {
          text: row.is_virtual ? bbn._("Update structure in options") : bbn._("Store structure as options"),
          action: this.toOption,
          icon: 'nf nf-md-opera',
        }, {
          text: bbn._("Duplicate"),
          action: this.duplicate,
          icon: 'nf nf-md-content_copy',
        }, {
          text: bbn._("Drop"),
          action: this.drop,
          icon: 'nf nf-md-trash_can',
        }] : [];
      },
      createDb(){
        this.getPopup({
          label: bbn._("New database"),
          scrollable: true,
          component: 'appui-database-db-form',
          componentOptions: {
            host: this.source.id,
            engine: this.source.engine,
            charset: this.source.charset || '',
            collation: this.source.collation || ''

          }
        })
      },
      analyze(db) {
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
      drop(db, opt = false){
        this.getPopup({
          label: false,
          component: this.$options.components.drop,
          componentOptions: {
            host: this.source.id,
            database: db.name || this.getRef('table').currentSelected,
            options: !!db.is_virtual
          },
          componentEvents: {
              success: () => {
                this.getRef('table').updateData();
              }
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
    },
    components: {
      drop: {
        template: `
          <bbn-form class="bbn-lpadding bbn-c bbn-lg"
                    :action="action"
                    :source="formSource"
                    submit-text="` + bbn._('Yes') + `"
                    cancel-text="` + bbn._('No') + `"
                    :prefilled="true"
                    @success="onSuccess"
                    @error="onError">
            <div bbn-text="message"/>
            <div class="bbn-light bbn-i">(` + bbn._("this action is irreversible") + `)</div>
            <div bbn-if="options"
                 class="bbn-middle bbn-top-space">
              <bbn-switch bbn-model="formSource.options"
                          class="bbn-right-sspace"/>
              <span>` + bbn._("Delete from options") + `</span>
            </div>
          </bbn-form>
        `,
        props: {
          host: {
            type: String,
            required: true
          },
          database: {
            type: [String, Array],
            required: true
          },
          options: {
            type: Boolean,
            default: false
          }
        },
        data(){
          return {
            action: appui.plugins['appui-database'] + '/actions/database/drop',
            formSource: {
              host_id: this.host,
              db: this.database,
              options: false
            },
            message: bbn.fn.isString(this.database) ?
              bbn._("Are you sure you want to drop the database %s ?", this.database) :
              bbn._("Are you sure you want to drop the selected databases?")
          }
        },
        methods: {
          onSuccess(d){
            if (d.success) {
              this.$emit('success');
              if (d.error) {
                appui.error(d.error);
              }
              else {
                appui.success();
              }
            }
            else {
              appui.error(d.error || bbn._('An error occurred'));
            }
          },
          onError(d){
            appui.error(d.error || bbn._('An error occurred'));
          }
        }
      }
    }
  }
})();
