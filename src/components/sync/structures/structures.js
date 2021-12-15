(() => {
  return {
    data(){
      return {
        root: appui.plugins['appui-database'] + '/',
        tables: this.source.structuresFiles,
        currentTable: null,
        currentFile: null,
        lastReception: false
      }
    },
    computed: {
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
      refreshFile(){
        this.confirm(bbn._('Are you sure you want to update the conflicts of this table?'), () => {
          this.post(this.root + 'actions/sync/structures/refresh', {
            table: this.currentTable
          }, d => {
            if (d.success) {
              appui.success();
            }
          })
        })
      },
      receive(structuresFiles){
        this.tables.splice(0, this.tables.length, ...structuresFiles);
        this.lastReception = dayjs();
        this.getRef('table').updateData();
      }
    },
    created(){
      appui.register('appui-database-sync-structures', this);
      try {
        let sync = appui.getRegistered('appui-database-sync');
        if (bbn.fn.isVue(sync)
          && bbn.fn.isFunction(sync.startStructuresPoller)
        ) {
          sync.startStructuresPoller();
        }
      }
      catch (e) {
        bbn.fn.log(e);
      }
    },
    beforeDestroy(){
      appui.unregister('appui-database-sync-structures');
    },
    components: {
      compare: {
        template: `
          <div class="bbn-middle">
            <i class="bbn-right-sspace nf nf-fa-times bbn-red bbn-middle"
               v-if="!source[field]"
               style="height:2.1em"/>
            <bbn-button icon="nf nf-mdi-vector_difference"
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
        data(){
          return {
            conflicts: appui.getRegistered('appui-database-sync-structures'),
            sync: appui.getRegistered('appui-database-sync')
          }
        },
        methods: {
          openDiff(){
            let currents = [];
            bbn.fn.iterate(this.source, (v, f) => {
              if (this.sync.source.dbs.includes(f) && (f !== this.field)){
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
                table: this.conflicts.currentTable,
                json: true
              }
            });
          }
        }
      }
    }
  }
})();