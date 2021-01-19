(() => {
  return {
    data(){
      return {
        root: appui.plugins['appui-databases'] + '/',
        tables: this.source.files,
        currentTable: null,
        currentFile: null,
        tableVisible: false
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
      }
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
               style="height:2.1em">
            </i>
            <bbn-button icon="nf nf-oct-git_compare"
                        @click="openDiff"
                        :notext="true"
                        v-else>
            </bbn-button>
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
              component: 'appui-databases-sync-diff',
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
      }
    }
  }
})();