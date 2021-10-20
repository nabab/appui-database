// Javascript Document
/*
*
*/

(()=>{
  const defaultColumn = {
    name: "",
    type: "",
    max_length: null,
    unsigned: "",
    decimal: null,
    nullable: "",
    default_value: "",
    index: "",
    defaultExpression: 0,
    extra: "",
  };
  return {
    props: ['source'],
    data () {
      let data = {
        name: this.name,
        comment: this.comment
      };
      return {
        name: '',
        comment: '',
        columns: [],
        edited: -1,
        formData: data,
      };
    },
    methods: {
      addColumn(idx, cfg) {
        bbn.fn.log("Add column", idx, cfg);
        if (this.columns[idx]) {
          this.columns.splice(idx, 0, bbn.fn.extend({}, defaultColumn, cfg || {}));
        }
        else {
          this.columns.push(bbn.fn.extend({}, defaultColumn, cfg || {}));
          idx = this.columns.length - 1;
        }
        this.edited = idx;
      },
      onCancel() {
        this.columns.splice(this.edited, 1);
        this.edited = -1;
      },
      getColDescription(col) {
        let str = col.type;
        if (col.max_length) {
          str += " (" + col.max_length + ")";
        }
        if (!col.nullable) {
          str += " NOT NULL";
        }
        if (col.default_value !== undefined) {
          str += " DEFAULT ";
          if (col.default_value === null) {
            str += "NULL";
          }
          else if (!col.defaultExpression && bbn.fn.isString(col.default_value)) {
            str += '"' + bbn.fn.replaceAll(col.default_value, '"', '\\"') + '"';
          }
          else {
            str += col.default_value;
          }
        }
        return str;
      }
    },
    watch: {
      values (v) {
        if (!v.length) {
          this.source.extra = "";
        }
        else {
          this.source.extra = v.map(a => {
            "'" + bbn.fn.replaceAll(a, "'", "\\'") + "'";
          }).join(",");
        }
      }
    }
  };
})();