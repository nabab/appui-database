(() => {
  return {
    data(){
      return {
        root: appui.plugins['appui-database'] + '/',
        tables: this.source.conflictsFiles,
        currentTable: null,
        currentFile: null,
        tableVisible: false,
        selected: [],
        currentTableTotal: 0,
        lastReception: false
      }
    },
    computed: {
      currentTableDate(){
        if (this.currentTable) {
          let d = bbn.fn.getField(this.tables, 'date', {value: this.currentTable});
          if (d && dayjs(d).isValid()) {
            return dayjs(d).format('DD/MM/YYYY');
          }
        }
        return false;
      },
      currentTableTime(){
        if (this.currentTable) {
          let d = bbn.fn.getField(this.tables, 'date', {value: this.currentTable});
          if (d && dayjs(d).isValid()) {
            return dayjs(d).format('HH:mm:ss');
          }
        }
        return false;
      },
      currentLastReceptionDate(){
        if (this.lastReception && this.lastReception.isValid()) {
          return this.lastReception.format('DD/MM/YYYY');
        }
        return false;
      },
      currentLastReceptionTime(){
        if (this.lastReception && this.lastReception.isValid()) {
          return this.lastReception.format('HH:mm:ss');
        }
        return false;
      }
    },
    methods: {
      renderJSON(row, col) {
        bbn.fn.log(row);
        let o = bbn.fn.isString(row.id) ? JSON.parse(row.id) : row.id;
        if (o) {
          let st = '';
          let i = 0;
          bbn.fn.iterate(o, (v, n) => {
            if (i) {
              st += '<br>';
            }
            st += '<strong>' + n + ':</strong> ' + v;
            i++;
          });

          return st;
        }
        return row[col.field];
      },
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
      receive(conflictsFiles){
        let oldTables = bbn.fn.extend(true, [], this.tables);
        this.tables.splice(0, this.tables.length, ...conflictsFiles);
        this.lastReception = dayjs();
        this.getRef('tablesList').updateData();
        if (this.currentTable) {
          let idx = bbn.fn.search(conflictsFiles, {value: this.currentTable});
          if (idx === -1) {
            this.currentTable = null;
          }
          else if (bbn.fn.getField(oldTables, 'date', {value: this.currentTable}) !== conflictsFiles[idx].date) {
            this.loadDiff(this.currentTable);
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
          },
          height: 180,
          width: 430
        });
      }
    },
    created(){
      appui.register('appui-database-sync-conflicts', this);
      try {
        let sync = appui.getRegistered('appui-database-sync');
        if (bbn.fn.isVue(sync)
          && bbn.fn.isFunction(sync.startConflictsPoller)
        ) {
          sync.startConflictsPoller();
        }
      }
      catch (e) {
        bbn.fn.log(e);
      }
    },
    beforeDestroy(){
      appui.unregister('appui-database-sync-conflicts');
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
               bbn-if="!source[field]"
               style="height:2.1em"/>
            <bbn-button icon="nf nf-mdi-vector_difference"
                        @click="openDiff"
                        :notext="true"
                        text="` + bbn._('Compare') + `"
                        bbn-else/>
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
        data(){
          return {
            conflicts: appui.getRegistered('appui-database-sync-conflicts')
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
                currents: currents,
                table: this.conflicts.currentTable
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
  <div class="bbn-padding bbn-overlay bbn-middle">
    <div>
      <div class="bbn-c bbn-bottom-space">` + bbn._('Select the source from where the data will be taken and copied to the different databases') + `</div>
      <div class="bbn-c">
        <bbn-dropdown :source="conflicts.source.dbs"
                      bbn-model="formData.source"/>
      </div>
    </div>
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