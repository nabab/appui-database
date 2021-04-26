// Javascript Document
(() => {
  return {
    data(){
      return {
        orientation: 'horizontal',
        root: appui.plugins['appui-database'] + '/',
        force: false,
        toolbar: [],
        hasMultipleSelected: false
      };
    },
    methods:{
      getToolbar(){
        let ar = [{
					text: bbn._("Create database"),
          action: this.showDbCreation
        }, {
					text: bbn._("Refresh host"),
          action: () => {
            this.closest('bbn-container').reload()
          }
        }];
        if (this.hasMultipleSelected) {
          ar.push({
            content: '<span>' + bbn._("With selected") + '</span>'
          });
          ar.push({
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
      showDbCreation(){
        this.getPopup({
          title: bbn._("New database"),
          component: 'appui-database-db-form',
          width: 500,
          height: '20em',
          source: {
            host_id: this.source.info.id
          }
        })
      },
      analyze(db) {
        if (!bbn.fn.isString(db)) {
          db = this.getRef('table').currentSelected;
        }

        bbn.fn.post(this.root + 'actions/database/analyze', {
          host_id: this.source.info.id,
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
          host_id: this.source.info.id,
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
              host_id: this.source.info.id,
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
        bbn.fn.link(this.root + 'tabs/' + this.source.engine + '/' + this.source.host + '/export');
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
        return '<a href="' + this.root + 'tabs/' + this.source.engine + '/' + this.source.host + '/' + row.name + '" class="bbn-b' +
          (col ? ' bbn-' + col : '') +
          '">' + row.name + '</a>';
      },
      refresh(row){
        bbn.fn.log(',----',row, arguments, 'fine')
        //appui.confirm(bbn._('Are you sure you want to import|refresh this database?'), () => {
          this.post(this.root + 'actions/database/update', {
            engine: this.source.engine,
            host: this.source.host,
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
      checkMultipleSelected(){
        bbn.fn.log('checkMultipleSelected');
        let t = this.getRef('table');
        if (t) {
          if (t.currentSelected.length > 1) {
            if (!this.hasMultipleSelected) {
              this.hasMultipleSelected = true;
            }
          }
          else if (this.hasMultipleSelected) {
            this.hasMultipleSelected = false;
          }
        }
      }
    },
    mounted(){
      this.$nextTick(() => {
        this.toolbar = this.getToolbar();
        this.closest("bbn-container").addMenu({
          text: bbn._('Change orientation'),
          icon: 'nf nf-fa-compass',
          action: () => {
            this.orientation = this.orientation === 'horizontal' ? 'vertical' : 'horizontal';
          }
        })
      });
    },
    watch: {
      hasMultipleSelected(v) {
        bbn.fn.log("toolbar chanfing???");
        this.toolbar = this.getToolbar();
      }
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
