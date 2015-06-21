import java.io.BufferedInputStream;
import java.io.File;
import java.io.FileInputStream;
import java.io.IOException;
import java.util.PriorityQueue;
import java.util.Queue;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpServletRequest;


import org.eclipse.jetty.server.Handler;
import org.eclipse.jetty.server.Server;
import org.eclipse.jetty.server.handler.ContextHandlerCollection;
import org.eclipse.jetty.servlet.ServletContextHandler;
import org.eclipse.jetty.servlet.ServletHolder;
import org.eclipse.jetty.webapp.WebAppContext;





public class getmedata
{
	
	public static void main( String args[] ) throws Exception
	{
		Server server = new Server( 8080 );
		
	//	Context root = new Context( server, "/", Context.SESSIONS );
		
		WebAppContext webapp = new WebAppContext();
        webapp.setContextPath( "/www" );
        webapp.setResourceBase( "web" );
        
        ServletContextHandler context0 = new ServletContextHandler(ServletContextHandler.SESSIONS);
        context0.setContextPath("/api");
        
        context0.addServlet( new ServletHolder( new PigAction() ), "/getmedata" );
        
        ContextHandlerCollection contexts = new ContextHandlerCollection();
        contexts.setHandlers(new Handler[] { context0, webapp });
        
        mysqlstuff dao = new mysqlstuff();
        dao.readDataBase();
        
        server.setHandler( contexts );
		
		server.start();
	}

}

class PigAction extends HttpServlet {

	private static final long serialVersionUID = -444303659399506881L;
	
	@Override
	public void init() throws ServletException
	{
		super.init();
		
		this.getServletContext().setAttribute( "pigs", new PriorityQueue<Integer>() );
	}

	@Override
	public void doGet( HttpServletRequest request, HttpServletResponse response ) throws ServletException, IOException
	{
		String action = request.getParameter( "action" );
		
		if( "arrival".equals( action ) ) {
			
			int result = -1;
			
			synchronized( getServletContext() ) {
				@SuppressWarnings( "unchecked" )
				Queue<Integer> queue = (PriorityQueue<Integer>) getServletContext().getAttribute( "pigs" );
				if( queue.size() > 0 ) {
					result = queue.poll();
					getServletContext().setAttribute( "getmedata", queue );
				}
			}
			
			response.getWriter().print( result );
		}
		
		if( "departure".equals( action ) ) {
			
			String y = request.getParameter( "y" );
			
			boolean result;
			
			synchronized( getServletContext() ) {				
				@SuppressWarnings( "unchecked" )
				Queue<Integer> queue = (PriorityQueue<Integer>) getServletContext().getAttribute( "pigs" );
				
				result = queue.add( Integer.valueOf( y ) );
				
				getServletContext().setAttribute( "getmedata", queue );
			}
			
			response.getWriter().print( result );		
		}
		response.setContentType( "text/html" );
		response.setStatus( HttpServletResponse.SC_OK );
		response.getWriter().println( "<h1>Hello</h1>" );			
	}

	
}

class HelloServlet extends HttpServlet {

	private static final long serialVersionUID = -2396391783958286261L;

	@Override
	public void init() throws ServletException
	{
		super.init();
		
		File out_dir = new File( "out" );
		
		if( !out_dir.exists() ) {
			out_dir.mkdir();
		}
	}
	
	@Override
	public void doPost( HttpServletRequest request, HttpServletResponse response ) throws ServletException, IOException
	{
		response.setContentType( "text/html" );
		response.setStatus( HttpServletResponse.SC_OK );
		response.getWriter().println( "<h1>Hello</h1>" );
	}
	
	


}
