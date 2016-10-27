import java.sql.*;  
class MysqlCon2{  
public static void main(String args[])
{  
String url = "";
String username = "";
String password = "";
Connection conn=null;
Statement stmt = null;
System.out.println("Connecting database...");
try 
{
	Class.forName("com.mysql.jdbc.Driver");
} 
catch (ClassNotFoundException e) 
{
	System.out.println("java AV --Where is your MySQL JDBC Driver?");
	e.printStackTrace();
	return;
}
try{

conn = DriverManager.getConnection(url, username, password);
if (conn!= null) {
		System.out.println("You made it, take control your database now!");
	} else {
		System.out.println("Failed to make connection!");
	}
    
stmt = conn.createStatement();
String sql = "CREATE TABLE FEED6" +
         "(item_id VARCHAR( 32 ) NOT NULL ,"+
	"feed_url VARCHAR( 512 ) NOT NULL ,"+
	 "item_content VARCHAR( 4000 ) ,"+
	 "item_title VARCHAR( 255 ) ,"+
	 "item_date TIMESTAMP NOT NULL ,"+
	 "item_url VARCHAR( 512 ) NOT NULL ,"+
	 "item_status CHAR( 2 ) ,"+
	 
          "PRIMARY KEY ( item_id ) ,"+ 
	 "fetch_date TIMESTAMP NOT NULL )"
            ; 

      stmt.executeUpdate(sql);
      System.out.println("Created table in given ");

} catch (SQLException e) {
    throw new IllegalStateException("AV --- Error!", e);
}
finally{
      //finally block used to close resources
      try{
         if(stmt!=null)
            conn.close();
      }catch(SQLException se){
      }// do nothing
      try{
         if(conn!=null)
            conn.close();
      }catch(SQLException se){
         se.printStackTrace();
      }//end finally try
   }



}

}  
