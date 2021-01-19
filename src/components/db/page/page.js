// Javascript Document
(() => {
  return {
    data(){
      return {
        orientation: "horizontal",
        root: appui.databases.source.root
      };
    },
    methods:{
      delete(row){
        let admin = appui.app.user.isAdmin;
        this.confirm('Do you want to remove this table?', () => {
          this.post(this.root + 'actions/database/remove_virtual', {
            row: row,
            admin: admin
          }, (d) => {
            if(d.success){
              appui.success(bbn._('Table successfully removed'));
              this.$nextTick(()=>{
                this.$refs.table.updateData();
              })
            }
            else{
              appui.error(bbn._('Something went wrong while removing the table'));
            }
          })  
            /*this.confirm('Do you want to remove the option from history?', () => {
              this.post(this.root + 'actions/database/remove_virtual', {
                row: row,
                admin: admin
              }, (d) => {
                bbn.fn.log(d)
              })    
            }, () => {
              
                this.post(this.root + 'actions/database/remove_virtual', {row:row}, (d) => {
                  bbn.fn.log(d)
                })
              
            })*/
          
          
        })
      },
      buttons(row){
        let res = [];
        if ( row.is_real ){
          res.push({
            title: bbn._('Refresh table\'s structure'),
            action: this.refresh,
            icon: 'nf nf-fa-refresh',
            notext: true
          });
        }
        else{
          res.push({
            title: bbn._('Delete virtual table'),
            action: this.delete,
            icon: 'nf nf-fa-trash_o',
            notext: true
            })
        }
        return res;
      },
    
      getStateColor(row){
       
        let col = false;
        if (
          (row.num_columns !== row.num_real_columns) ||
          (row.num_keys !== row.num_real_keys)
        ){
          col = 'orange';
          if ( !row.is_real ){
            col = 'red';
          }
          else if ( !row.is_virtual ){
            col = 'purple';
          }
        }
        else if ( row.is_same ){
          col = 'green';
        }
        return col;
      },
      writeURL(row){
        return this.root + 'tabs/' + this.source.engine + '/' + this.source.host + '/' + this.source.db + '/' + row.name;
      },
      writeTable(row){
        let col = this.getStateColor(row);
        if ( row.is_real ){
          return '<a href="' + this.root + 'tabs/' + this.source.engine + '/' +this.source.host + '/' + this.source.db + '/' + row.name + '" class="bbn-b' +
          (col ? ' bbn-' + col : '') +
          '">' + row.name + '</a>';
        }
        else {
          return '<span title="'+ bbn._('The table is not real') +'" href="' + this.root + 'tabs/' + this.source.engine + '/' + this.source.host + '/' + this.source.db + '/' + row.name + '" class="bbn-b' +
          (col ? ' bbn-' + col : '') +
          '">' + row.name + '</span>';
        }
      },
      refresh(row){
        appui.confirm(bbn._('Are you sure you want to refresh this table?'), () => {
          this.post(this.root + 'actions/table/update', {
            engine: this.source.engine,
            host: this.source.host,
            db: this.source.db,
            table: row.name
          }, (d) => {
            if ( d.success ){
              this.$refs.table.updateData();
            }
            bbn.fn.log("RESULT", this.$refs.table, d);
          });
          bbn.fn.log("refresh", arguments);
        });
      }
    },
    mounted(){
      this.$nextTick(() => {
        bbn.vue.closest(this, ".bbn-container").addMenu({
          text: bbn._('Change orientation'),
          icon: 'nf nf-fa-compass',
          click(a){
            this.orientation = this.orientation === 'horizontal' ? 'vertical' : 'horizontal';
          }
        })
      });
    }
  }
})();