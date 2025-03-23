// Javascript Document

(() => {
  return {
    props: {
      host: {
        type: String,
      },
      engine: {
        type: String,
        default: 'mysql'
      },
      database: {
        type: String,
      }
    },
    data() {
      return {
        code: '',
        codes: [],
        database: '',
        result: [],
        numberOfQueries: 1
      };
    },
    methods: {

    },
    components: {
      request: {
        template: `
<div class="bbn-w-90 bbn-margin bbn-padding bbn-radius bbn-border bbn-c">
  <div class="bbn-w-100 bbn-padding bbn-flex-width bbn-c" v-if="mode === 'write'">
    <!--bbn-dropdown v-model="currentLanguage"
                  :source="['MySQL', 'MariaDB']"
                  :placeholder="_('Choose Database type')"/>
    <bbn-dropdown v-model="currentHost"
                  :source="['clovis_dev@db.m3l.co']"
                  :placeholder="_('Choose host')"/-->
    <bbn-dropdown v-model="currentDatabase"
                  :source="databases"
                  :value="database"
                  :autobind="true"
                  />
  </div>
  <div class="bbn-w-100 bbn-padding">
    <bbn-code v-model="currentValue"
              class="bbn-w-100"
              mode="sql"
              style="width:100%"
              :wrap="false"
              ref="code"
              :autosize="true"
              :readonly="mode === 'read'"
              :scrollable="false"
              :fill="false"/>
  </div>
  <div class="bbn-w-100 bbn-vpadding" v-if="mode === 'write'">
    <bbn-button class="bbn-w-100" @click="exec" icon="nf nf-cod-run_all">
      Run query
    </bbn-button>
  </div>
</div>
        `,
        mixins: [
          bbn.cp.mixins.basic,
          bbn.cp.mixins.input,
        ],
        props: {
          database: {
            type: String,
            default: '',
          },
          databases: {
            type: Array,
          },
          mode: {
            type: String,
            default: "read",
          }
        },
        data() {
          return {
            currentValue: this.value,
            currentDatabase: this.database
          };
        },
        methods: {
          exec() {
            bbn.fn.post(appui.plugins["appui-database"] + '/console', {
              code: this.currentValue,
              database: this.currentDatabase
            }, data => {
              this.result = data.data;
              let res = this.currentValue + data.str_tab;
              this.$parent.codes.push({code:res});
              this.currentValue = "";
            });
          }
        },
        mounted() {
          const code = this.getRef('code');
          if (code) {
            //code.widget.setOption('lineWrapping', false);
          }
        },
        watch: {
          currentValue(v) {
          	this.emitInput(v);
          },
          value(v) {
            this.currentValue = v;
          }
        }
      }
    }
  };
})();
