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
        }
      };
    },
    computed: {
      toolbar(){
        let ar = [{
          icon: 'nf nf-md-database_plus',
					label: bbn._("Create database"),
          action: this.showDbCreation
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
      }
    },
    methods:{
      formatBytes: bbn.fn.formatBytes,
      getTableButtons(row){
        return [{
          text: bbn._("Analyze"),
          action: this.analyze
        }, {
          text: row.isVirtual ? bbn._("Update structure in options") : bbn._("Store structure as options"),
          action: this.toOption
        }, {
          text: bbn._("Duplicate"),
          action: this.duplicate
        }, {
          text: bbn._("Drop"),
          action: this.drop
        }]
      },
      showDbCreation(){
        this.getPopup({
          label: bbn._("New database"),
          component: 'appui-database-db-form',
          width: 500,
          height: '20em',
          source: {
            host_id: this.source.id
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
      drop(db) {
        this.confirm(
          (bbn.fn.isString(db) ? bbn._("Are you sure you want to drop the database %s ?", db) : bbn._("Are you sure you want to drop these databases?"))
          + '<br>'
          + bbn._("This action is irreversible"),
          () => {
            if (!db) {
              db = this.getRef('table').currentSelected;
            }
            bbn.fn.post(this.root + 'actions/database/drop', {
              host_id: this.source.id,
              db: db
            }, d => {
              if (d.success) {
                let t = this.getRef('table');
                if (bbn.fn.isString(db)) {
                  db = [db];
                }
                bbn.fn.each(db, a => {
                  if (!d.undeleted || !d.undeleted.includes(a)) {
                    t.delete(t.getIndex(a), false);
                  }
                });
                if (d.error) {
	                appui.error(d.error);
                }
              }
              else if (d.error) {
                appui.error(d.error);
              }
              bbn.fn.log('success')
            })
          }
        )
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
      dropdown: {
        props: ['source'],
        template: `<bbn-dropdown :source="src"
        												 placeholder="` + bbn._("Choose an action") + `"
							                   @change="select"/>`,
        data() {
          let host = this.closest('appui-database-host');
          let r = [{
            text: bbn._("Analyze"),
            value: 'analyze'
          }, {
            text: this.source.isVirtual ? bbn._("Update structure in options") : bbn._("Store structure as options"),
            value: 'toOption'
          }, {
            text: bbn._("Duplicate"),
            value: 'duplicate'
          }, {
            text: bbn._("Drop"),
            value: 'drop'
          }];
          return {
            host: host,
            src: r
          }
        },
        methods: {
          select(code) {
            bbn.fn.log("select", code, this.source);
            if (bbn.fn.isFunction(this.host[code])) {
              this.host[code](this.source.name)
            }
          }
        }
      }
    }
  }
})();
