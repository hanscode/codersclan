<section class="section section-alerts">
    <div class="container">

       <p>
	@if ( get_sub_field('select_alert') == 'success' )

          @php the_sub_field('success_msg') @endphp  <!-- display success message-->

        @elseif ( get_sub_field('select_alert') == 'error' )

            @php the_sub_field('error_msg') @endphp <!-- display error message -->
       @else

         @php the_sub_field('warning_msg') @endphp <!-- display warning message -->

      @endif
	</p>

    </div>
</section>
