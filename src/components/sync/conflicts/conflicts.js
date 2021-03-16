(() => {
  return {
    data(){
      return {
        root: appui.plugins['appui-database'] + '/',
        tables: this.source.files,
        currentTable: null,
        currentFile: null,
        tableVisible: false,
        selected: [],
        currentTableTotal: 0
      }
    },
    computed: {
      currentTableDate(){
        if (this.currentTable) {
          let d = bbn.fn.getField(this.tables, 'date', {value: this.currentTable});
          if (d && moment(d).isValid()) {
            return moment(d).format('DD/MM/YYYY');
          }
        }
        return false;
      },
      currentTableTime(){
        if (this.currentTable) {
          let d = bbn.fn.getField(this.tables, 'date', {value: this.currentTable});
          if (d && moment(d).isValid()) {
            return moment(d).format('HH:mm:ss');
          }
        }
        return false;
      }
    },
    methods: {
      refreshFile(){
        this.confirm(bbn._('Are you sure you want to update the conflicts of this table (this could take a long time)?'), () => {
          this.post(this.root + 'actions/sync/conflicts/refresh', {
            table: this.currentTable
          }, d => {
            if (d.success) {
              appui.success();
            }
          })
        })
      },
      loadDiff(table){
        this.tableVisible = false;
        this.currentFile = bbn.fn.getField(this.tables, 'file', {value: table});
        this.$nextTick(() => {
          this.tableVisible = true;
        })
      },
      receive(data){
        if ('conflictsFiles' in data) {
          let oldTables = bbn.fn.extend(true, [], this.tables);
          this.tables.splice(0, this.tables.length, ...data.conflictsFiles);
          this.getRef('tablesList').updateData();
          if (this.currentTable) {
            let idx = bbn.fn.search(data.conflictsFiles, {value: this.currentTable});
            if (idx === -1) {
              this.currentTable = null;
            }
            else {
              if (bbn.fn.getField(oldTables, 'date', {value: this.currentTable}) !== data.conflictsFiles[idx].date) {
                this.loadDiff(this.currentTable);
              }
            }
          }
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
          this._fix([row.id]);
        }
      },
      fixSelected(){
        if (this.selected) {
          this._fix(this.selected);
        }
      },
      fixAll(){
        if (this.currentTableTotal) {
          this._fix('all');
        }
      },
      setWatch(){
        let table = this.getRef('table');
        table.$watch('currentSelected', n => {
          this.selected.splice(0, this.selected.length, ...n);
        });
        table.$watch('total', n => {
          this.currentTableTotal = n;
        });
      },
      _remove(id){
        this.post(this.root + 'actions/sync/conflicts/remove', {
          id: id,
          filename: this.currentFile
        }, d => {
          if (d.success) {
            this.getRef('table').updateData();
            appui.success();
          }
          else {
            appui.error();
          }
        })
      },
      _fix(ids){
        this.getPopup().open({
          title: bbn._('Select the data source'),
          component: this.$options.components.fixForm,
          source: {
            ids: ids,
            table: this.currentTable,
            filename: this.currentFile
          }
        });
      }
    },
    created(){
      appui.register('appui-database-sync-conflicts', this);
    },
    watch: {
      currentTable(newVal){
        this.tableVisible = false;
        if (newVal) {
          this.loadDiff(newVal)
        }
      }
    },
    components: {
      compare: {
        template: `
          <div class="bbn-middle">
            <i class="bbn-right-sspace nf nf-fa-times bbn-red bbn-middle"
               v-if="!source[field]"
               style="height:2.1em"/>
            <bbn-button icon="nf nf-oct-git_compare"
                        @click="openDiff"
                        :notext="true"
                        text="` + bbn._('Compare') + `"
                        v-else/>
          </div>
        `,
        props: {
          source: {
            type: Object
          },
          field: {
            type: String
          }
        },
        methods: {
          openDiff(){
            let currents = [];
            bbn.fn.iterate(this.source, (v, f) => {
              if ((f !== 'id') && (f !== this.field)){
                currents.push({
                  db: f,
                  data: v
                });
              }
            })
            this.getPopup({
              title: bbn._("Records"),
              width: '90%',
              component: 'appui-database-sync-diff',
              source: {
                origin: {
                  db: this.field,
                  data: this.source[this.field]
                },
                currents: currents
              }
            });
          }
        }
      },
      fixForm: {
        template: `
<bbn-form :action="conflicts.root + 'actions/sync/conflicts/fix'"
          @success="afterSubmit"
          :data="source"
          :source="formData"
          :confirm-message="mess">
  <div class="bbn-spadded bbn-grid-fields">
  <label>` + bbn._('Source') + `</label>
  <bbn-dropdown :source="conflicts.source.dbs"
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
          let conflicts = appui.getRegistered('appui-database-sync-conflicts');
          return {
            conflicts: conflicts,
            formData: {
              source: ''
            },
            mess: bbn.fn.isArray(this.source.ids)
              ? (this.source.ids.length > 1
                ? bbn._('Are you sure you want to fix the selected records?')
                : bbn._('Are you sure you want to fix this record?'))
              : bbn._('Are you sure you want to fix all records?')
          }
        },
        methods: {
          afterSubmit(d){
            if (d.success) {
              this.conflicts.getRef('table').updateData();
              appui.success();
            }
            else {
              appui.error();
            }
          }
        }
      }
    }
  }
})();