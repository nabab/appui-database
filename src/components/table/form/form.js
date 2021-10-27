// Javascript Document
/*
*
*/

(()=>{
  const defaultColumn = {
    name: "",
    type: "",
    maxlength: null,
    signed: 0,
    decimal: null,
    'null': "",
    default: "",
    index: "",
    defaultExpression: 0,
    extra: "",
  };
  return {
    props: ['source'],
    data () {
      let data = {
        name: this.name,
        comment: this.comment,
        columns: []
      };
      return {
        root: appui.plugins["appui-database"] + '/',
        name: '',
        comment: '',
        edited: -1,
        formData: data,
      };
    },
    computed: {
      formButtons() {
        return ["cancel", {
          text: bbn._("Submit"),
          action: ()=> {
            let form = this.$refs.form;
          	form.submit();
          },
          disabled: false //form.isValid,
        }];
      }
    },
    methods: {
      addColumn(idx, cfg) {
        bbn.fn.log("Add column", idx, cfg);
        if (this.formData.columns[idx]) {
          this.formData.columns.splice(idx, 0, bbn.fn.extend({}, defaultColumn, cfg || {}));
        }
        else {
          this.formData.columns.push(bbn.fn.extend({}, defaultColumn, cfg || {}));
          idx = this.formData.columns.length - 1;
        }
        this.edited = idx;
      },
      onCancel() {
        this.formData.columns.splice(this.edited, 1);
        this.edited = -1;
      },
      getColDescription(col) {
        let str = col.type;
        if (col.maxlength) {
          str += " (" + col.maxlength + ")";
        }
        if (!col.null) {
          str += " NOT NULL";
        }
        if (col.default !== undefined) {
          str += " DEFAULT ";
          if (col.default === null) {
            str += "NULL";
          }
          else if (!col.defaultExpression && bbn.fn.isString(col.default)) {
            str += '"' + bbn.fn.replaceAll('"', '\\"', col.default) + '"';
          }
          else {
            str += col.default;
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