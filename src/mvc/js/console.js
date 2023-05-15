// Javascript Document

(() => {
  return {
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
        mixins: [
          bbn.vue.basicComponent,
          bbn.vue.inputComponent,
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
        template: document.getElementById("bbn-tpl-appui-database-console-request"),
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
            code.widget.setOption('lineWrapping', false);
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