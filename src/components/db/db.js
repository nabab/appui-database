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
      return {
        orientation: 'horizontal',
        root: appui.plugins['appui-database'] + '/',
        force: false,
        toolbar: [],
        hasMultipleSelected: false,
        currentData: this.source,
        ready: !!this.source,
      };
    },
    methods:{
      getToolbar(){
        let ar = [{
					text: bbn._("Create table"),
          action: this.showTableCreation
        }, {
					text: bbn._("Refresh host"),
          action: () => {
            this.closest('bbn-container').reload();
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
        if (this.currentData?.is_real) {
          ar.push({
            content: '<span class="bbn-iblock">#' + bbn._("Size") +
            	': </span>&nbsp;<span class="bbn-b bbn-iblock">' +
            	this.currentData.size + '</span>'
          });
        }
        return ar;
      },
      showTableCreation(){
        this.getPopup({
          title: bbn._("New table"),
          component: 'appui-database-table-form',
          data: this.cfg,
          width: "120em",
          height: "60em",
          source: {
            host: this.currentData.host,
            engine: this.currentData.engine,
            db: this.currentData.db,
            table_id: this.currentData.info.id,
            types: this.currentData.types,
            predefined: this.currentData.predefined,
            constraints: this.currentData.constraints
          }
        });
      },
      analyze(db) {
        if (!bbn.fn.isString(db)) {
          db = this.getRef('table').currentSelected;
        }

        bbn.fn.post(this.root + 'actions/table/analyze', {
          host_id: this.currentData.info.id,
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
        bbn.fn.post(this.root + 'actions/table/options', {
          host_id: this.currentData.info.id,
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
      drop(t) {
        let cp = this;
        this.confirm(
          (bbn.fn.isString(t) ? bbn._("Are you sure you want to drop the table %s ?", t) : bbn._("Are you sure you want to drop these tables?")) +
          '<br>' + bbn._("This action is irreversible"),
          () => {
            if (!t) {
              t = this.getRef('table').currentSelected;
           }
            bbn.fn.post(this.root + 'actions/table/drop', {
              host: this.currentData.host,
              db: this.currentData.db,
              engine: this.currentData.engine,
              table: t
            }, d => {
              if (d.success) {
                t = this.getRef('table');
                t.currentSelected.splice(0, t.currentSelected.length);
                this.$nextTick(() => {
                  t.updateData();
                });
                appui.success(bbn._("Table dropped!"));
              }
              else if (d.error) {
                appui.error(d.error);
              }
            });
          }
        );
      },
      exportDb(){
        //this.closest('bbn-router').route('host/export')
        bbn.fn.link(this.root + 'tabs/' + this.currentData.engine + '/' + this.currentData.host + '/export');
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
        return '<a href="' + this.root + 'tabs/' + this.currentData.engine + '/' + this.currentData.host + '/' + row.name + '" class="bbn-b' +
          (col ? ' bbn-' + col : '') +
          '">' + row.name + '</a>';
      },
      refresh(row){
        bbn.fn.log(',----',row, arguments, 'fine');
        //appui.confirm(bbn._('Are you sure you want to import|refresh this database?'), () => {
          this.post(this.root + 'actions/database/update', {
            engine: this.currentData.engine,
            host: this.currentData.host,
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
      },

    /*
      delete(row){
        let admin = appui.user.isAdmin;
        this.confirm('Do you want to remove this table?', () => {
          this.post(this.root + 'actions/database/remove_virtual', {
            row: row,
            admin: admin
          }, (d) => {
            if(d.success){
              appui.success(bbn._('Table successfully removed'));
              this.confirm('Do you want to remove the option from history?', () => {
                this.post(this.root + 'actions/database/remove_virtual', {
                  row: row,
                  admin: admin
                }, (d) => {
                  this.$nextTick(()=>{
                    this.$refs.table.updateData();
                  })
                })    
              }, () => {
                this.$nextTick(()=>{
                  this.$refs.table.updateData();
                })
              })
            }
            else{
              appui.error(bbn._('Something went wrong while removing the table'));
            }
          })
        })
      },
      buttons(row){
        let res = [];
        if ( row.is_real ){
          res.push({
            title: bbn._('Refresh table\'s structure'),
            action: this.refresh,
            icon: 'nf nf-fa-refresh',
            notext: true
          });
        }
        else{
          res.push({
            title: bbn._('Delete virtual table'),
            action: this.delete,
            icon: 'nf nf-fa-trash_o',
            notext: true
            })
        }
        return res;
      },

      getStateColor(row){

        let col = false;
        if (
          (row.num_columns !== row.num_real_columns) ||
          (row.num_keys !== row.num_real_keys)
        ){
          col = 'orange';
          if ( !row.is_real ){
            col = 'red';
          }
          else if ( !row.is_virtual ){
            col = 'purple';
          }
        }
        else if ( row.is_same ){
          col = 'green';
        }
        return col;
      },
      writeURL(row){
        return this.root + 'tabs/' + this.currentData.engine + '/' + this.currentData.host + '/' + this.currentData.db + '/' + row.name;
      },
      writeTable(row){
        let col = this.getStateColor(row);
        if ( row.is_real ){
          return '<a href="' + this.root + 'tabs/' + this.currentData.engine + '/' +this.currentData.host + '/' + this.currentData.db + '/' + row.name + '" class="bbn-b' +
          (col ? ' bbn-' + col : '') +
          '">' + row.name + '</a>';
        }
        else {
          return '<span title="'+ bbn._('The table is not real') +'" href="' + this.root + 'tabs/' + this.currentData.engine + '/' + this.currentData.host + '/' + this.currentData.db + '/' + row.name + '" class="bbn-b' +
          (col ? ' bbn-' + col : '') +
          '">' + row.name + '</span>';
        }
      },
      refresh(row){
        appui.confirm(bbn._('Are you sure you want to refresh this table?'), () => {
          this.post(this.root + 'actions/table/update', {
            engine: this.currentData.engine,
            host: this.currentData.host,
            db: this.currentData.db,
            table: row.name
          }, (d) => {
            if ( d.success ){
              this.$refs.table.updateData();
            }
            bbn.fn.log("RESULT", this.$refs.table, d);
          });
          bbn.fn.log("refresh", arguments);
        });
      }
      */
    },
    mounted() {
      if (!this.ready) {
        bbn.fn.post(appui.plugins['appui-database'] + '/data/db', {
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
        this.toolbar = this.getToolbar();
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
          let db = this.closest('appui-database-db');
          let r = [{
            text: bbn._("Analyze"),
            value: 'analyze'
          }, {
            text: db.currentData.isVirtual ? bbn._("Update structure in options") : bbn._("Store structure as options"),
            value: 'toOption'
          }, {
            text: bbn._("Duplicate"),
            value: 'duplicate'
          }, {
            text: bbn._("Drop"),
            value: 'drop'
          }];
          return {
            db: db,
            src: r
          };
        },
        methods: {
          select(code) {
            bbn.fn.log("select", code, this.source);
            if (bbn.fn.isFunction(this.db[code])) {
              this.db[code](this.db.currentData.name);
            }
          }
        }
      }
    }
  };
})();
