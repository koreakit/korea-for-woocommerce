describe( 'Korea for WooCommerce General Settings', function() {
    const baseURL = Cypress.env('url');

	before(function() {
        cy.visit( baseURL + '/wp-login.php' );
        cy.wait( 1000 );
        cy.get( '#user_login' ).type( Cypress.env( 'users' ).admin.username );
        cy.get( '#user_pass' ).type( Cypress.env( 'users' ).admin.password );
        cy.get( '#wp-submit' ).click();
    });

    it( 'can activate postcode', function() {
		cy.visit( baseURL + '/wp-admin/admin.php?page=wc-settings&tab=integration&section=korea' );

		cy.get( '#woocommerce_korea_postcode_yn' ).check();

		cy.get( '.woocommerce-save-button' ).click();

		cy.get('#message').should('have.class', 'updated');
    } );
} );
