// Javascript Document

(() => {
  return {
    props: {
      isForeignKey: {
        type: Boolean,
        default: false
      },
      engine: {
        type: String,
        default: 'mysql'
      },
      host: {
        type: String,
      },
      db: {
        type: String
      },
      table: {
        type: String,
      },
      column: {
        type: String
      }
    },
    computed: {
      isConstraint(){
        return false;
      },
      displayValue() {
        if (this.isForeignKey) {
          return 'not a foreign key';
        }
        let res = '';
        bbn.fn.post(appui.plugins['appui-database'] + '/external-values', {
          data: {
            engine: this.engine,
            host: this.host,
            db: this.db,
            table: this.table,
            column: this.column
          }
        }, d => {
          bbn.fn.log("DISPLAY VALUE", d);
        });
        return res;
      }
    },
    methods: {
      copy() {
        bbn.fn.copy(this.source.id);
      },
      goto(){
        //bbn.fn.log(this.source);
      }
    }
  };
})();
