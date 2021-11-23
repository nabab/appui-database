// Javascript Document
(() => {
  return {
    data(){
      return {
        root: appui.plugins['appui-database'] + '/',
        selected: []
      }
    },
    methods: {
      renderVals(row){
        if (row.vals) {
          return Object.keys(row.vals).join(', ')
        }
        return '-';
      },
      renderDiff(row){
        return row.diff ? '<i class="nf nf-fa-exclamation_triangle bbn-red"></i>' : '';
      },
      renderDate(row){
        return row.dt && dayjs(row.dt).isValid() ? dayjs(row.dt).format('DD/MM/YYYY HH:mm:ss') : '';
      },
      getButtons(row){
        let ar = [{
          text: bbn._('See source'),
          icon: 'nf nf-mdi-ray_start',
          notext: true,
          action: row => {
            this.diff(row, row.db)
          }
        }, {
          text: bbn._('See destinations'),
          icon: 'nf nf-mdi-ray_end',
          notext: true,
          action: row => {
            this.diff(row, bbn.fn.filter(this.source.dbs, db => db !== row.db))
          }
        }, {
          text: bbn._('See differences dbs'),
          icon: 'nf nf-mdi-ray_vertex',
          notext: true,
          action: row => {
            this.diffDbs(row, bbn.fn.filter(this.source.dbs, db => db !== row.db))
          }
        }, {
          text: bbn._('Fix'),
          notext: true,
          icon: 'nf nf-mdi-auto_fix',
          action: this.fix,
          disabled: !row.diff
        }, {
          text: bbn._('Delete'),
          icon: 'nf nf-fa-trash',
          notext: true,
          action: this.remove
        }];
        return ar;
      },
      setWatch(){
        this.getRef('table').$watch('currentSelected', n => {
          this.selected.splice(0, this.selected.length, ...n);
        });
      },
      diff(row, db){
        if (row && db) {
          if (!bbn.fn.isArray(db)) {
            db = [db];
          }
          this.post(this.root + 'data/sync/diff/db', {
            id: row.id,
            db: db
          }, d => {
            if (d.success) {
              if (!d.data.length) {
                this.alert(bbn._("This record doesn't exist anymore in the database(s)"));
              }
              else {
                this.getPopup({
                  title: bbn._("Records"),
                  width: '90%',
                  component: 'appui-database-sync-diff',
                  source: {
                    origin: {
                      db: 'sync',
                      data: row.vals
                    },
                    currents: d.data,
                    table: row.tab
                  }
                });
              }
            }
            else {
              appui.error()
            }
          });
        }
      },
      diffDbs(row, dbs){
        if (row && dbs) {
          if (!bbn.fn.isArray(dbs)) {
            dbs = [dbs];
          }
          this.post(this.root + 'data/sync/diff/dbs', {
            id: row.id,
            dbs: dbs
          }, d => {
            if (d.success) {
              if (!d.data.length) {
                this.alert(bbn._("This record doesn't exist anymore in the databases"));
              }
              else {
                this.getPopup({
                  title: bbn._("Records"),
                  width: '90%',
                  component: 'appui-database-sync-diff',
                  source: {
                    origin: {
                      db: row.db,
                      data: d.from.data
                    },
                    currents: d.data,
                    table: row.tab
                  }
                });
              }
            }
            else {
              appui.error()
            }
          });
        }
      },
      remove(row){
        if (row.id) {
          this.confirm(bbn._('Are you sure you want to delete this record from sync?'), () => {
            this._remove(row.id);
          })
        }
      },
      removeSelected(){
        if (this.selected) {
          this.confirm(bbn._('Are you sure you want to delete the selected records from sync?'), () => {
            this._remove(this.selected);
          })
        }
      },
      fix(row){
        if (row.id) {
          this._fix(row.id);
        }
      },
      fixSelected(){
        if (this.selected) {
          this._fix(this.selected);
        }
      },
      scanClear(){
        this.confirm(bbn._('Are you sure you want to scan all records and delete those with equal values ​​on the various databases?'), () => {
          this.post(this.root + 'actions/sync/errors/clear', d => {
            if (d.success && (d.deleted !== undefined)) {
              this.getRef('table').updateData();
              appui.success(bbn._("%d rows deleted.", parseInt(d.deleted)));
            }
          })
        })
      },
      _remove(id){
        this.post(this.root + 'actions/sync/errors/remove', {id: id}, d => {
          if (d.success) {
            this.getRef('table').updateData();
            appui.success();
          }
          else {
            appui.error();
          }
        })
      },
      _fix(id){
        this.getPopup().open({
          title: bbn._('Select the data source'),
          component: this.$options.components.fixForm,
          source: {
            id: id
          }
        });
      }
    },
    created(){
      appui.register('appui-database-sync-errors', this);
    },
    components: {
      expander: {
        template: `
<div class="bbn-w-100">
  <div v-if="hasRows"
       class="bbn-w-50">
    <div class="bbn-header bbn-spadded">` + bbn._('Rows') + `</div>
    <div class="bbn-xspadded bbn-bordered bbn-w-100">
      <bbn-json-editor :value="source.rows" readonly></bbn-json-editor>
    </div>
  </div>
  <div :class="{'bbn-w-50': hasRows, 'bbn-w-100': !hasRows}">
    <div class="bbn-header bbn-spadded">` + bbn._('Values') + `</div>
    <div class="bbn-xspadded bbn-bordered bbn-w-100">
      <bbn-json-editor :value="source.vals" readonly></bbn-json-editor>
    </div>
  </div>
</div>
        `,
        props: {
          source: {
            type: Object
          }
        },
        data(){
          return {
            hasRows: !!Object.keys(this.source.rows).length
          }
        }
      },
      fixForm: {
        template: `
<bbn-form :action="errors.root + 'actions/sync/errors/fix'"
          @success="afterSubmit"
          :data="source"
          :source="formData"
          :confirm-message="mess">
  <div class="bbn-spadded bbn-grid-fields">
  <label>` + bbn._('Source') + `</label>
  <bbn-dropdown :source="list"
                v-model="formData.source"/>
  </div>
</bbn-form>
        `,
        props: {
          source: {
            type: Object
          }
        },
        data(){
          let errors = appui.getRegistered('appui-database-sync-errors');
          return {
            errors: errors,
            formData: {
              source: ''
            },
            list: ['sync', ...errors.source.dbs],
            mess: bbn.fn.isArray(this.source.id) ?
              bbn._('Are you sure you want to fix the selected records?') :
              bbn._('Are you sure you want to fix this record?')
          }
        },
        methods: {
          afterSubmit(d){
            if (d.success) {
              this.errors.getRef('table').updateData();
              appui.success();
            }
            else {
              appui.error();
            }
          }
        }
      }
    }
  };
})();
